<!-- Page 1: Bienvenida -->
<section class="w-[100vw] h-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-black via-black/50 to-yellow-900/20 relative overflow-hidden">
    <div class="text-center px-4 max-w-md relative z-10">
        <img src="{{ asset('images/el_turril.webp') }}" alt="El Turril Logo" class="h-24 sm:h-40 mx-auto mb-6 transition-transform duration-500 hover:scale-105 animate-shine" style="filter: drop-shadow(0 0 20px #f1c31a);">

        <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in" style="animation-delay: 0.2s;">
            <span class="text-yellow-400">EL TURRIL</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 animate-fade-in" style="animation-delay: 0.4s;">
            Sabor único, preparación tradicional
        </p>
        <p class="text-lg max-w-2xl mx-auto mb-10 animate-fade-in" style="animation-delay: 0.6s;">
            Descubre nuestros sandwiches con carne cocinada al turril durante horas, 
            logrando ese sabor ahumado que nos caracteriza.
        </p>

        <p class="text-sm text-gray-400 mt-10 animate-pulse-slow">
            Desliza horizontalmente para explorar el Menú →
        </p>

        <!-- Ícono animado de swipe para móvil (oculto en desktop, ahora con más espacio abajo del texto) -->
        <div class="swipe flex justify-center items-center mt-6 mb-4 z-20 sm:hidden">
            <div class="path"></div>
            <div class="hand-icon animated" 
                style="background-image: url('{{ asset('images/hand-icon.svg') }}');
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: contain;
                        width: 50px;
                        height: 50px;
                        position: relative;
                        transform-origin: 50% 70%;
                        z-index: 20;">
            </div>
        </div>
    </div>

    <!-- Icono de scroll down en esquina inferior derecha (solo en pantallas grandes) -->
    <div class="icon-scroll hidden lg:block absolute bottom-4 right-4 z-20"></div>
    
    <!-- Fondo sutil con animación -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIxIiBmaWxsPSIjZjFjMzFhIiBvcGFjaXR5PSIwLjEiLz48L3N2Zz4=')] opacity-10 animate-pulse"></div>
</section>