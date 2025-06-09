<x-filament-panels::page>
<div class="flex h-full min-h-[calc(100vh-theme(spacing.16))]">
        {{-- Contenedor principal --}}
        <div class="flex-1 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
            {{-- Encabezado del POS --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">EL TURRIL POS</h1>
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                    {{ number_format($currentCashBoxBalance, 2) }} Bs.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
                {{-- Columna del Menú (Izquierda) --}}
                <div class="md:col-span-2 bg-white dark:bg-gray-900 p-4 rounded-lg shadow-inner flex flex-col">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Menú</h2>

                    {{-- Filtros de Tipo de Producto --}}
                    <div class="flex space-x-2 mb-4">
                        <x-filament::button
                            wire:click="setProductTypeFilter(null)"
                            :color="$selectedProductType === null ? 'primary' : 'gray'"
                        >
                            Todo
                        </x-filament::button>
                        <x-filament::button
                            wire:click="setProductTypeFilter('sandwich')"
                            :color="$selectedProductType === 'sandwich' ? 'primary' : 'gray'"
                        >
                            Sándwiches
                        </x-filament::button>
                        <x-filament::button
                            wire:click="setProductTypeFilter('drink')"
                            :color="$selectedProductType === 'drink' ? 'primary' : 'gray'"
                        >
                            Bebidas
                        </x-filament::button>
                        {{-- Añade más botones de filtro para otros tipos si los tienes --}}
                    </div>


                    <div class="flex-1 overflow-y-auto pr-2">
                        {{-- Sección Sándwiches --}}
                        <h3 class="text-xl font-medium mb-3 text-gray-700 dark:text-gray-200">Sándwiches</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
                            @foreach ($menuProducts as $product)
                                @if ($product['type'] === 'sandwich')
                                    <button 
                                        wire:click="addToCart({{ $product['id'] }}, '{{ $product['name'] }}', {{ $product['price'] }})"
                                        class="bg-blue-500 dark:bg-gray-800 hover:shadow-lg hover:scale-105 transition-transform duration-150 ease-in-out text-gray-700 dark:text-gray-200 font-semibold py-6 px-4 rounded-2xl shadow flex flex-col items-center justify-center text-center border border-gray-100 dark:border-gray-700"
                                    >
                                        <img 
                                            src="{{ $product['image'] 
                                                ? asset('storage/products/' . $product['image']) 
                                                : asset('images/no-image.png') 
                                            }}" 
                                            alt="{{ $product['name'] }}" 
                                            class="w-20 h-20 object-cover mb-3 rounded-full shadow"
                                        />
                                        <span class="text-base md:text-lg font-bold">{{ $product['name'] }}</span>
                                        <span class="text-sm md:text-base mt-2 font-medium">{{ number_format($product['price'], 2) }} Bs.</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>




                        {{-- Sección Bebidas --}}
                        <h3 class="text-xl font-medium mb-3 text-gray-700 dark:text-gray-200">Bebidas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
                            @foreach ($menuProducts as $product)
                                @if ($product['type'] === 'drink')
                                    <button 
                                        wire:click="addToCart({{ $product['id'] }}, '{{ $product['name'] }}', {{ $product['price'] }})"
                                        class="bg-green-500 dark:bg-green-700 hover:bg-green-600 dark:hover:bg-green-800 text-grey-700 font-semibold py-6 px-4 rounded-2xl shadow flex flex-col items-center justify-center text-center transition duration-150 ease-in-out border border-green-200 dark:border-green-800 hover:shadow-lg hover:scale-105"
                                    >
                                        {{-- <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-20 h-20 object-cover mb-3 rounded-full shadow"> --}}
                                        <span class="text-base md:text-lg font-bold">{{ $product['name'] }}</span>
                                        <span class="text-sm md:text-base mt-2 font-medium">{{ number_format($product['price'], 2) }} Bs.</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>


                        {{-- Puedes añadir más secciones (Combos, Otros) aquí --}}
                    </div>
                </div>

                {{-- Columna del Carrito (Derecha) --}}
                <div class="md:col-span-1 bg-white dark:bg-gray-900 p-4 rounded-lg shadow-inner flex flex-col">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Carrito</h2>

                    <div class="flex-1 overflow-y-auto pr-2">
                        @forelse ($cart as $index => $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($item['unit_price'], 2) }} Bs. x {{ $item['quantity'] }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="decreaseQuantity({{ $index }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <x-heroicon-o-minus-circle class="w-5 h-5" />
                                    </button>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ number_format($item['subtotal'], 2) }}</span>
                                    <button wire:click="increaseQuantity({{ $index }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <x-heroicon-o-plus-circle class="w-5 h-5" />
                                    </button>
                                    <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:text-red-700">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">El carrito está vacío.</p>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center text-xl font-bold mb-4 text-gray-900 dark:text-white">
                            <span>Total:</span>
                            <span>{{ number_format($total, 2) }} Bs.</span>
                        </div>

                        {{-- Selector de Método de Pago (ejemplo básico) --}}
                        <div class="mb-4">
                            <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pago:</label>
                            <select wire:model="paymentMethod" id="paymentMethod" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                            class="w-full"
                        >
                            Realizar Pedido
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
