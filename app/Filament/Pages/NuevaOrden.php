<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CurrentStock;
use App\Models\CashBox;
use App\Models\CashMovement;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification; // Para notificaciones
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NuevaOrden extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart'; // Icono para el sidebar
    protected static string $view = 'filament.pages.nueva-orden'; // La vista Blade
    protected static ?string $navigationLabel = 'Nueva Orden'; // Texto en el sidebar
    protected static ?string $title = 'Punto de Venta'; // Título de la página
    protected static ?string $slug = 'pos'; // URL de la página: /admin/pos

    // Propiedades Livewire para el estado de la UI
    public $menuProducts = []; // Productos disponibles en el menú
    public $cart = []; // Array para almacenar los ítems en el carrito
    public $total = 0; // Total del carrito
    public $currentCashBoxBalance = 0; // Balance actual de la caja
    public $paymentMethod = 'Efectivo'; // Método de pago por defecto

    // Opcional: Propiedades para filtrar el menú
    public $selectedProductType = null; // 'sandwich', 'drink', etc.

    public function mount(): void
    {
        // Cargar productos del menú al iniciar la página
        $this->loadMenuProducts();
        $this->loadCashBoxBalance();
    }

    protected function loadMenuProducts(): void
    {
        // Cargar todos los productos activos y con gestión de stock
        // Puedes agregar categorías o tipos aquí si lo necesitas para el menú
        $query = Product::where('active', true)->where('stock_management', true);

        if ($this->selectedProductType) {
            $query->where('type', $this->selectedProductType);
        }

        $this->menuProducts = $query->get()->toArray(); // Convierte a array para facilitar el manejo en Livewire
    }

    protected function loadCashBoxBalance(): void
    {
        // Obtener el balance de la caja de la sucursal del usuario actual
        // Necesitas asegurarte de que el usuario tenga una branch_id y una caja asociada
        $userBranchId = Auth::user()->branch_id;
        if ($userBranchId) {
            $cashBox = CashBox::where('branch_id', $userBranchId)
                               ->where('status', 'open') // Asume que la caja está abierta
                               ->first();
            $this->currentCashBoxBalance = $cashBox ? $cashBox->current_balance : 0;
        }
    }

    public function addToCart(int $productId, string $productName, float $productPrice): void
    {
        // Buscar si el producto ya está en el carrito
        $found = false;
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] === $productId) {
                $this->cart[$key]['quantity']++;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $item['unit_price'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->cart[] = [
                'product_id' => $productId,
                'name' => $productName,
                'unit_price' => $productPrice,
                'quantity' => 1,
                'subtotal' => $productPrice,
            ];
        }

        $this->calculateTotal();
    }

    public function removeFromCart(int $index): void
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // Reindexar el array
            $this->calculateTotal();
        }
    }

    public function increaseQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['quantity']++;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
            $this->calculateTotal();
        }
    }

    public function decreaseQuantity(int $index): void
    {
        if (isset($this->cart[$index]) && $this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
            $this->calculateTotal();
        } elseif (isset($this->cart[$index]) && $this->cart[$index]['quantity'] === 1) {
            $this->removeFromCart($index);
        }
    }

    protected function calculateTotal(): void
    {
        $this->total = array_sum(array_column($this->cart, 'subtotal'));
    }

    public function placeOrder(): void
    {
        if (empty($this->cart)) {
            Notification::make()
                ->title('El carrito está vacío')
                ->danger()
                ->send();
            return;
        }

        // Obtener la sucursal del usuario
        $userBranchId = Auth::user()->branch_id;
        if (!$userBranchId) {
            Notification::make()
                ->title('Error: Usuario sin sucursal asignada.')
                ->danger()
                ->send();
            return;
        }

        // Obtener la caja activa
        $cashBox = CashBox::where('branch_id', $userBranchId)
                           ->where('status', 'open')
                           ->first();

        if (!$cashBox) {
            Notification::make()
                ->title('Error: No hay caja abierta para esta sucursal.')
                ->danger()
                ->send();
            return;
        }

        DB::beginTransaction();
        try {
            // 1. Crear la Venta
            $sale = Sale::create([
                'branch_id' => $userBranchId,
                'user_id' => Auth::user()->id,
                'total_amount' => $this->total,
                'discount_amount' => 0, // Puedes agregar lógica para descuentos
                'final_amount' => $this->total,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
                'notes' => 'Venta desde POS',
            ]);

            // 2. Agregar ítems a la Venta y Actualizar Stock
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['subtotal'], // Total después de descuento por ítem (aquí sin descuento)
                ]);

                // Lógica de Descuento de Stock
                $product = Product::find($item['product_id']);

                if ($product->stock_management) {
                    if ($product->type === 'sandwich') {
                        // Descontar ingredientes de la receta
                        $recipe = $product->recipes->first(); // Asumiendo que un sandwich tiene 1 receta principal
                        if ($recipe) {
                            foreach ($recipe->ingredients as $ingredient) {
                                $neededQuantity = $ingredient->pivot->quantity * $item['quantity'];
                                $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                            ->where('ingredient_id', $ingredient->id)
                                                            ->first();
                                if ($currentStock && $currentStock->quantity >= $neededQuantity) {
                                    $currentStock->decrement('quantity', $neededQuantity);
                                    StockMovement::create([
                                        'branch_id' => $userBranchId,
                                        'ingredient_id' => $ingredient->id,
                                        'type' => 'exit',
                                        'quantity' => $neededQuantity,
                                        'unit' => $ingredient->unit,
                                        'user_id' => Auth::user()->id,
                                        'description' => 'Consumo por venta de ' . $product->name,
                                    ]);
                                } else {
                                    // Manejar stock insuficiente (opcional: revertir transacción, notificar)
                                    DB::rollBack();
                                    Notification::make()
                                        ->title('Stock insuficiente para ' . $ingredient->name)
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }
                        }
                    } else if ($product->type === 'drink' || $product->is_combo) {
                        // Descontar el producto directamente (ej. Coca-Cola)
                        $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                    ->where('product_id', $product->id)
                                                    ->first();
                        $neededQuantity = $item['quantity'];

                        if ($currentStock && $currentStock->quantity >= $neededQuantity) {
                            $currentStock->decrement('quantity', $neededQuantity);
                            StockMovement::create([
                                'branch_id' => $userBranchId,
                                'product_id' => $product->id,
                                'type' => 'exit',
                                'quantity' => $neededQuantity,
                                'unit' => 'unidades', // Asume unidad para productos terminados
                                'user_id' => Auth::user()->id,
                                'description' => 'Venta de producto ' . $product->name,
                            ]);
                        } else {
                            DB::rollBack();
                            Notification::make()
                                ->title('Stock insuficiente para ' . $product->name)
                                ->danger()
                                ->send();
                            return;
                        }
                    }
                    // Considerar lógica para 'combo' si es más complejo que solo descontar productos directos
                }
            }

            // 3. Registrar Movimiento de Caja
            $cashBox->increment('current_balance', $sale->final_amount);
            CashMovement::create([
                'cash_box_id' => $cashBox->id,
                'user_id' => Auth::user()->id,
                'type' => 'sale_income',
                'amount' => $sale->final_amount,
                'description' => 'Ingreso por venta #' . $sale->id,
                'related_sale_id' => $sale->id,
            ]);

            DB::commit();

            Notification::make()
                ->title('Pedido realizado con éxito!')
                ->success()
                ->send();

            // Limpiar el carrito y actualizar el balance de la caja
            $this->cart = [];
            $this->total = 0;
            $this->loadCashBoxBalance();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error al realizar el pedido: ' . $e->getMessage())
                ->danger()
                ->send();
            // Logear el error para debugging
            Log::error('Error al realizar pedido POS: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }

    public function setProductTypeFilter(?string $type): void
    {
        $this->selectedProductType = $type;
        $this->loadMenuProducts();
    }
}
