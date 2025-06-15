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

    protected static ?string $modelLabel = 'Nueva Orden';

    protected static ?string $navigationGroup = 'Ventas';
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
    public $todaySalesTotal = 0;


    // Opcional: Propiedades para filtrar el menú
    public $selectedProductType = null; // 'sandwich', 'drink', etc.

    public static function canAccess(): bool
    {
        // Verificar si el usuario tiene permiso para acceder a esta página
        return Auth::check() && Auth::user()->can('access_pos_page');
    }

    public function mount(): void
    {
        // Cargar productos del menú al iniciar la página
        $this->loadMenuProducts();
        $this->loadCashBoxBalance();
        $this->loadTodaySalesTotal();
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

    protected function loadTodaySalesTotal(): void
    {
        $userBranchId = Auth::user()->branch_id;

        // Ventas de hoy para esta sucursal (ajusta según tu modelo)
        $this->todaySalesTotal = \App\Models\Sale::where('branch_id', $userBranchId)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('final_amount');
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

        $userBranchId = Auth::user()->branch_id;
        if (!$userBranchId) {
            Notification::make()
                ->title('Error: Usuario sin sucursal asignada.')
                ->danger()
                ->send();
            return;
        }

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
                'discount_amount' => 0,
                'final_amount' => $this->total,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
                'notes' => 'Venta desde POS',
            ]);

            // 2. Agregar ítems a la Venta y Registrar Movimientos de Stock
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);

                if ($product->stock_management) {
                    if ($product->type === 'sandwich') {
                        $recipe = $product->recipes->first();
                        if ($recipe) {
                            foreach ($recipe->ingredients as $ingredient) {
                                $neededQuantity = $ingredient->pivot->quantity * $item['quantity'];
                                $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                            ->where('ingredient_id', $ingredient->id)
                                                            ->first();

                                if (!$currentStock || $currentStock->quantity < $neededQuantity) { // Verificar stock antes de crear el movimiento
                                    DB::rollBack();
                                    Notification::make()
                                        ->title('Stock insuficiente para ' . $ingredient->name)
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // AQUI: SOLO CREA EL MOVIMIENTO DE STOCK. EL OBSERVER SE ENCARGARÁ DE ACTUALIZAR CURRENT_STOCK.
                                StockMovement::create([
                                    'branch_id' => $userBranchId,
                                    'ingredient_id' => $ingredient->id,
                                    'type' => 'exit',
                                    'quantity' => $neededQuantity,
                                    'unit' => $ingredient->unit,
                                    'user_id' => Auth::user()->id,
                                    'description' => 'Consumo por venta de ' . $product->name . ' (Orden #' . $sale->id . ')',
                                ]);
                            }
                        }
                    } else if ($product->type === 'drink') {
                        $neededQuantity = $item['quantity'];
                        $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                    ->where('product_id', $product->id)
                                                    ->first();

                        if (!$currentStock || $currentStock->quantity < $neededQuantity) { // Verificar stock antes de crear el movimiento
                            DB::rollBack();
                            Notification::make()
                                ->title('Stock insuficiente para ' . $product->name)
                                ->danger()
                                ->send();
                            return;
                        }

                        // AQUI: SOLO CREA EL MOVIMIENTO DE STOCK. EL OBSERVER SE ENCARGARÁ DE ACTUALIZAR CURRENT_STOCK.
                        StockMovement::create([
                            'branch_id' => $userBranchId,
                            'product_id' => $product->id,
                            'type' => 'exit',
                            'quantity' => $neededQuantity,
                            'unit' => 'unidades',
                            'user_id' => Auth::user()->id,
                            'description' => 'Venta de producto ' . $product->name . ' (Orden #' . $sale->id . ')',
                        ]);
                    } else if ($product->is_combo) {
                    $comboItems = \App\Models\ComboItem::where('combo_product_id', $product->id)->get();

                    foreach ($comboItems as $comboItem) {
                        $comboProduct = \App\Models\Product::find($comboItem->product_id);
                        $comboNeededQuantity = $item['quantity'] * $comboItem->quantity;

                        if ($comboProduct->type === 'sandwich') {
                            $recipe = $comboProduct->recipes->first();
                            if ($recipe) {
                                foreach ($recipe->ingredients as $ingredient) {
                                    $neededQuantity = $ingredient->pivot->quantity * $comboNeededQuantity;
                                    $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                                ->where('ingredient_id', $ingredient->id)
                                                                ->first();

                                    if (!$currentStock || $currentStock->quantity < $neededQuantity) {
                                        DB::rollBack();
                                        Notification::make()
                                            ->title('Stock insuficiente para ' . $ingredient->name . ' en el combo ' . $product->name)
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    StockMovement::create([
                                        'branch_id' => $userBranchId,
                                        'ingredient_id' => $ingredient->id,
                                        'type' => 'exit',
                                        'quantity' => $neededQuantity,
                                        'unit' => $ingredient->unit,
                                        'user_id' => Auth::user()->id,
                                        'description' => 'Consumo por venta de combo ' . $product->name . ' (Incluye sandwich ' . $comboProduct->name . ', ingrediente ' . $ingredient->name . ') (Orden #' . $sale->id . ')',
                                    ]);
                                }
                            }
                        } else if ($comboProduct->type === 'drink') {
                            $currentStock = CurrentStock::where('branch_id', $userBranchId)
                                                        ->where('product_id', $comboProduct->id)
                                                        ->first();

                            if (!$currentStock || $currentStock->quantity < $comboNeededQuantity) {
                                DB::rollBack();
                                Notification::make()
                                    ->title('Stock insuficiente para bebida ' . $comboProduct->name . ' en el combo ' . $product->name)
                                    ->danger()
                                    ->send();
                                return;
                            }

                            StockMovement::create([
                                'branch_id' => $userBranchId,
                                'product_id' => $comboProduct->id,
                                'type' => 'exit',
                                'quantity' => $comboNeededQuantity,
                                'unit' => 'unidades',
                                'user_id' => Auth::user()->id,
                                'description' => 'Venta de combo ' . $product->name . ' (Incluye bebida ' . $comboProduct->name . ') (Orden #' . $sale->id . ')',
                            ]);
                        }
                        // Puedes agregar más tipos aquí según tu sistema.
                        // else if ($comboProduct->is_combo) { ... } para combos anidados si lo necesitas.
                    }
                }
                }
            }

            // 3. Registrar Movimiento de Caja
            // AQUI: SOLO CREA EL MOVIMIENTO DE CAJA. EL OBSERVER SE ENCARGARÁ DE ACTUALIZAR CASHBOX.CURRENT_BALANCE.
            CashMovement::create([
                'cash_box_id' => $cashBox->id,
                'user_id' => Auth::user()->id,
                'type' => 'sale_income',
                'amount' => $sale->final_amount,
                'description' => 'Ingreso por venta #' . $sale->id,
                'related_sale_id' => $sale->id,
            ]);

            DB::commit();

            $this->loadTodaySalesTotal();

            Notification::make()
                ->title('Pedido realizado con éxito!')
                ->success()
                ->send();

            $this->cart = [];
            $this->total = 0;
            $this->loadTodaySalesTotal();
            $this->loadCashBoxBalance(); // Esta función probablemente depende de current_balance, ya se actualizará por el observer.
                                        // Si la necesitas para refrescar la vista, asegúrate que lea el valor de la base de datos.

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error al realizar el pedido: ' . $e->getMessage())
                ->danger()
                ->send();
            Log::error('Error al realizar pedido POS: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }

    public function setProductTypeFilter(?string $type): void
    {
        $this->selectedProductType = $type;
        $this->loadMenuProducts();
    }
}
