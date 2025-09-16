@php
    // Consultar la venta para obtener total y método de pago
    $sale = $lastSaleId ? \App\Models\Sale::find($lastSaleId) : null;
@endphp

<div class="relative space-y-6 p-6">

    <!-- Encabezado del pedido -->
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            Pedido #{{ $lastSaleId ?? 'N/A' }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Confirmación de tu pedido reciente
        </p>
    </div>

    <!-- Detalles del pedido -->
    <div class="space-y-4">
        <div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Productos</h4>
            @if ($lastSaleId)
                <ul class="mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    @foreach (\App\Models\SaleItem::where('sale_id', $lastSaleId)->get() as $item)
                        <li class="flex justify-between items-center">
                            <span class="flex-1 truncate">{{ $item->product->name ?? 'Producto no encontrado' }} (x{{ $item->quantity }})</span>
                            <span class="font-medium">{{ number_format($item->unit_price * $item->quantity, 2) }} Bs.</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No hay ítems disponibles.</p>
            @endif
        </div>

        <!-- Total y Método de Pago -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="flex justify-between items-center text-base font-semibold text-gray-900 dark:text-white">
                <span>Total</span>
                <span>{{ $sale ? number_format($sale->final_amount, 2) : '0.00' }} Bs.</span>
            </div>
            <div class="flex justify-between items-center mt-2 text-sm text-gray-600 dark:text-gray-400">
                <span>Método de Pago</span>
                <span>{{ $sale ? $sale->payment_method : 'No especificado' }}</span>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex flex-col sm:flex-row gap-4 mt-6">
        <x-filament::button wire:click="printTicket" color="primary" size="lg" class="flex-1 flex items-center justify-center">
            <x-heroicon-o-printer class="w-6 h-6 mr-2" />
            Ticket (Cocina)
        </x-filament::button>
        <x-filament::button wire:click="printReceipt" color="primary" size="lg" class="flex-1 flex items-center justify-center">
            <x-heroicon-o-printer class="w-6 h-6 mr-2" />
            Recibo (Cliente)
        </x-filament::button>
    </div>
</div>