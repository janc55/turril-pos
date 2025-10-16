<!-- Page 3: Combos -->
        <section class="w-[100vw] h-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-black via-black/50 to-yellow-900/20 relative overflow-hidden">
            <div class="container mx-auto h-full px-4 py-16 flex flex-col items-center justify-center">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-8 text-center">Combos Especiales</h2>
                <p class="text-white/70 mb-12 text-center max-w-lg">Combina tu sandwich favorito con papas y bebida para una experiencia completa.</p>
                <div class="grid md:grid-cols-2 gap-8 w-full max-w-4xl">
                    <!-- Combo 1 -->
                    <div class="group bg-black/50 rounded-lg overflow-hidden border border-yellow-400/20 hover:border-yellow-400/50 transition-all duration-300 transform hover:scale-105">
                        <img src="{{ asset('images/combos/image1.jpg') }}" alt="Combo Clásico" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-yellow-400 mb-2">Combo Clásico</h3>
                            <p class="text-white/80 mb-3">Sandwich de cerdo + papas + refresco. $18.99</p>
                            <button class="w-full bg-yellow-400 text-black py-2 rounded font-medium hover:bg-yellow-300 transition-colors">Pedir Ahora</button>
                        </div>
                    </div>
                    <!-- Combo 2 -->
                    <div class="group bg-black/50 rounded-lg overflow-hidden border border-yellow-400/20 hover:border-yellow-400/50 transition-all duration-300 transform hover:scale-105">
                        <img src="{{ asset('images/combos/image2.jpg') }}" alt="Combo Pollo" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-yellow-400 mb-2">Combo Pollo Ahumado</h3>
                            <p class="text-white/80 mb-3">Sandwich de pollo + ensalada + agua. $17.99</p>
                            <button class="w-full bg-yellow-400 text-black py-2 rounded font-medium hover:bg-yellow-300 transition-colors">Pedir Ahora</button>
                        </div>
                    </div>
                    <!-- Agrega más si es necesario -->
                </div>
                <button @click="nextPage()" class="mt-8 text-yellow-400 hover:text-white transition-colors text-lg">→ Contáctanos</button>
            </div>
        </section>