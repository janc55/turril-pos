<x-filament-panels::page>
    {{-- Contenedor principal que asegura que el layout ocupe al menos la altura de la ventana menos el header de Filament --}}
    <div class="flex h-full min-h-[calc(100vh-theme(spacing.16))]">
        <div class="flex-1 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md flex flex-col"> {{-- IMPORTANT: Añadido flex flex-col aquí para que su contenido (header y grid) pueda distribuir su altura --}}
            {{-- Encabezado del POS --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">EL TURRIL POS</h1>
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                    {{ number_format($currentCashBoxBalance, 2) }} Bs.
                </div>
            </div>

            {{-- Grid principal para Menú y Carrito --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[80vh]"> {{-- h-[80vh] limita el alto del grid al 80% del viewport --}}
                {{-- Columna del Menú (Izquierda) --}}
                <div class="md:col-span-2 bg-white dark:bg-gray-900 p-4 rounded-lg shadow-inner flex flex-col h-full max-h-[78vh]"> {{-- max-h para evitar overflow --}}
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Menú</h2>

                    {{-- Filtros de Tipo de Producto --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <x-filament::button
                            wire:click="setProductTypeFilter(null)"
                            :color="$selectedProductType === null ? 'primary' : 'gray'"
                            class="flex-1 min-w-[100px]"
                        >
                            Todo
                        </x-filament::button>
                        <x-filament::button
                            wire:click="setProductTypeFilter('sandwich')"
                            :color="$selectedProductType === 'sandwich' ? 'primary' : 'gray'"
                            class="flex-1 min-w-[100px]"
                        >
                            Sándwiches
                        </x-filament::button>
                        <x-filament::button
                            wire:click="setProductTypeFilter('drink')"
                            :color="$selectedProductType === 'drink' ? 'primary' : 'gray'"
                            class="flex-1 min-w-[100px]"
                        >
                            Bebidas
                        </x-filament::button>
                    </div>

        {{-- Contenedor desplazable de productos del Menú --}}
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            {{-- Sección Sándwiches --}}
            <h3 class="text-xl font-medium mb-3 text-gray-700 dark:text-gray-200">Sándwiches</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
                @foreach ($menuProducts as $product)
                    @if ($product['type'] === 'sandwich')
                        <button
                            wire:click="addToCart({{ $product['id'] }}, '{{ $product['name'] }}', {{ $product['price'] }})"
                            class="relative overflow-hidden text-yellow-400 font-semibold py-4 px-3 rounded-xl shadow flex flex-col items-center justify-center text-center transition duration-150 ease-in-out border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-105"
                            style="background-image: url('{{ $product['image'] ? asset('storage/' . $product['image']) : asset('images/no-image.png') }}'); background-size: cover; background-position: center;"
                        >
                            <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
                            <div class="relative z-10 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-base font-bold truncate w-full">{{ $product['name'] }}</span>
                                <span class="text-sm mt-1 font-medium">{{ number_format($product['price'], 2) }} Bs.</span>
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>

            {{-- Sección Bebidas --}}
            <h3 class="text-xl font-medium mb-3 text-gray-700 dark:text-gray-200">Bebidas</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
                @foreach ($menuProducts as $product)
                    @if ($product['type'] === 'drink')
                        <button
                            wire:click="addToCart({{ $product['id'] }}, '{{ $product['name'] }}', {{ $product['price'] }})"
                            class="relative overflow-hidden text-gray-800 dark:text-white font-semibold py-4 px-3 rounded-xl shadow flex items-center justify-center text-center transition duration-150 ease-in-out border border-green-200 dark:border-green-800 hover:shadow-lg hover:scale-105"
                            style="background-image: url('{{ $product['image'] ? asset('storage/' . $product['image']) : asset('images/no-image.png') }}'); background-size: cover; background-position: center;"
                        >
                            <div class="absolute inset-0 bg-black opacity-40"></div>
                            <div class="relative z-10 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-base font-bold truncate w-full">{{ $product['name'] }}</span>
                                <span class="text-sm mt-1 font-medium">{{ number_format($product['price'], 2) }} Bs.</span>
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>
            {{-- Sección Combos --}}
            <h3 class="text-xl font-medium mb-3 text-gray-700 dark:text-gray-200">Combo</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
                @foreach ($menuProducts as $product)
                    @if ($product['type'] === 'combo')
                        <button
                            wire:click="addToCart({{ $product['id'] }}, '{{ $product['name'] }}', {{ $product['price'] }})"
                            class="relative overflow-hidden text-gray-800 dark:text-white font-semibold py-4 px-3 rounded-xl shadow flex items-center justify-center text-center transition duration-150 ease-in-out border border-yellow-200 dark:border-yellow-800 hover:shadow-lg hover:scale-105"
                            style="background-image: url('{{ $product['image'] ? asset('storage/' . $product['image']) : asset('images/no-image.png') }}'); background-size: cover; background-position: center;"
                        >
                            <div class="absolute inset-0 bg-black opacity-40"></div>
                            <div class="relative z-10 flex flex-col items-center justify-center text-center w-full">
                                <span class="text-base font-bold truncate w-full">{{ $product['name'] }}</span>
                                <span class="text-sm mt-1 font-medium">{{ number_format($product['price'], 2) }} Bs.</span>
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Columna del Carrito (Derecha) --}}
    <div class="md:col-span-1 bg-white dark:bg-gray-900 p-4 rounded-lg shadow-inner flex flex-col h-full max-h-[78vh]">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Carrito</h2>

        {{-- Contenedor desplazable de ítems del Carrito --}}
        <div class="overflow-y-auto pr-2 custom-scrollbar max-h-[38vh]"> {{-- Ajusta este valor según el espacio necesario --}}
            @forelse ($cart as $index => $item)
                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <div class="flex-1 pr-2">
                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ number_format($item['unit_price'], 2) }} Bs. x {{ $item['quantity'] }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="decreaseQuantity({{ $index }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1 -m-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <x-heroicon-o-minus-circle class="w-5 h-5" />
                        </button>
                        <span class="font-bold text-gray-900 dark:text-white text-lg min-w-[50px] text-center">{{ number_format($item['subtotal'], 2) }}</span>
                        <button wire:click="increaseQuantity({{ $index }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1 -m-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <x-heroicon-o-plus-circle class="w-5 h-5" />
                        </button>
                        <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:text-red-700 p-1 -m-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900 transition">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">El carrito está vacío.</p>
            @endforelse
        </div>

        {{-- Pie del Carrito (Total y Botón de Pedido) --}}
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="flex justify-between items-center text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                <span>Total:</span>
                <span>{{ number_format($total, 2) }} Bs.</span>
            </div>

            {{-- Selector de Método de Pago --}}
            <div class="mb-4">
                <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pago:</label>
                <select wire:model="paymentMethod" id="paymentMethod" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-2 px-3">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="QR">QR</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>

            <x-filament::button
                wire:click="placeOrder"
                color="success"
                size="xl"
                class="w-full text-lg py-3"
            >
                Realizar Pedido
            </x-filament::button>
        </div>
    </div>
</div>

        </div>
    </div>
</x-filament-panels::page>

