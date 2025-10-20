<section class="w-[100vw] h-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-black via-black/50 to-yellow-900/20 relative overflow-hidden text-white">
    <div class="container mx-auto h-full px-4 py-16 flex flex-col items-center justify-center">
        <h2 class="text-3xl md:text-4xl font-bold text-[#f1c31a] mb-6 text-center animate-slide-in-top">
            Nuestro Menú 🍔
        </h2>
        <p class="text-white/80 mb-12 text-center max-w-lg animate-fade-in-slow">
            Sandwiches ahumados con carne desmenuzada, lentamente cocidos y servidos en pan estilo francés. ¡Frescos y listos para disfrutar!
        </p>

        <!-- Contenedor para grilla responsiva: horizontal scroll en móvil con snap -->
        <div class="w-full max-w-6xl overflow-x-auto md:overflow-visible pb-6 md:pb-0 -mx-4 md:mx-0 px-4 md:px-0 scrollbar-hide snap-x snap-mandatory md:snap-none flex flex-nowrap md:grid md:grid-cols-3 gap-4 md:gap-8" 
            x-ref="menuScroll"
            x-init="$nextTick(() => { if (currentPage === 1) menuContainer = $refs.menuScroll; })">
            {{-- Turrilito Mejorado --}}
            <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition duration-300 animate-slide-in-left group border border-gray-700 flex-shrink-0 w-80 max-w-full md:w-auto md:max-w-none snap-start">
                <img src="{{ asset('images/image1.webp') }}" alt="Turrilito" class="w-full h-40 md:h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 md:p-6 text-gray-100">
                    <h3 class="text-xl md:text-2xl font-extrabold text-[#f1c31a] mb-2 md:mb-3">Turrilito</h3>
                    <p class="text-gray-300 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3 md:line-clamp-none">
                        60 gr. de carne desmenuzada ahumada al turril, lentamente cocido y desmenuzado, cubierto con una salsa casera, y ensalada, todo servido en un pan estilo francés retostado.
                    </p>
                    <div class="flex flex-col gap-2 md:gap-3">
                        <!-- Precio Cerdo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de cerdo (usando archivo SVG externo) -->
                                <img src="{{ asset('images/pig.svg') }}" alt="Icono de cerdo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Cerdo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">12 Bs</span>
                        </div>
                        <!-- Precio Pollo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de pollo (similar) -->
                                <img src="{{ asset('images/chicken.svg') }}" alt="Icono de pollo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Pollo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">10 Bs</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Turrilazo Mejorado --}}
            <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition duration-300 animate-fade-in group border border-gray-700 flex-shrink-0 w-80 max-w-full md:w-auto md:max-w-none snap-start">
                <img src="{{ asset('images/image1.webp') }}" alt="Turrilazo" class="w-full h-40 md:h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 md:p-6 text-gray-100">
                    <h3 class="text-xl md:text-2xl font-extrabold text-[#f1c31a] mb-2 md:mb-3">Turrilazo</h3>
                    <p class="text-gray-300 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3 md:line-clamp-none">
                        80 gr. de carne desmenuzada ahumada al turril, lentamente cocido y desmenuzado, cubierto con una salsa casera, y ensalada, todo servido en un pan estilo francés retostado.
                    </p>
                    <div class="flex flex-col gap-2 md:gap-3">
                        <!-- Precio Cerdo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de cerdo (usando archivo SVG externo) -->
                                <img src="{{ asset('images/pig.svg') }}" alt="Icono de cerdo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Cerdo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">16 Bs</span>
                        </div>
                        <!-- Precio Pollo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de pollo (similar) -->
                                <img src="{{ asset('images/chicken.svg') }}" alt="Icono de pollo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Pollo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">14 Bs</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Super Turril Mejorado --}}
            <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition duration-300 animate-slide-in-right group border border-gray-700 flex-shrink-0 w-80 max-w-full md:w-auto md:max-w-none snap-start">
                <img src="{{ asset('images/image2.webp') }}" alt="Super Turril" class="w-full h-40 md:h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 md:p-6 text-gray-100">
                    <h3 class="text-xl md:text-2xl font-extrabold text-[#f1c31a] mb-2 md:mb-3">Super Turril</h3>
                    <p class="text-gray-300 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3 md:line-clamp-none">
                        120 gr. de carne desmenuzada ahumada al turril, lentamente cocido y desmenuzado, cubierto con una salsa casera, y ensalada, todo servido en un pan estilo francés retostado.
                    </p>
                    <div class="flex flex-col gap-2 md:gap-3">
                        <!-- Precio Cerdo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de cerdo (usando archivo SVG externo) -->
                                <img src="{{ asset('images/pig.svg') }}" alt="Icono de cerdo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Cerdo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">23 Bs</span>
                        </div>
                        <!-- Precio Pollo con icono -->
                        <div class="flex items-center justify-between p-2 md:p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center gap-2 md:gap-3">
                                <!-- Icono de pollo (similar) -->
                                <img src="{{ asset('images/chicken.svg') }}" alt="Icono de pollo" class="w-6 h-6 text-[#f1c31a]">
                                <span class="text-base md:text-lg font-semibold text-gray-100">Pollo</span>
                            </div>
                            <span class="bg-[#f1c31a] text-gray-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">21 Bs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Quitamos el botón de "Explora Combos" ya que las flechas de navegación lo hacen --}}
        <button @click="nextPage()" class="mt-8 text-yellow-400 hover:text-white transition-colors text-sm">→ Explora Combos</button> 
    </div>
</section>