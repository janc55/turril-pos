{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<div 
    x-data="pagesApp()" 
    x-init="init()"
    class="w-full h-screen overflow-hidden relative"
    @wheel.prevent.debounce.150ms="handleScroll($event)"
    @touchstart="handleTouchStart($event)"
    @touchmove="handleTouchMove($event)"
    @touchend="handleTouchEnd($event)"
>
    <div 
        x-ref="pagesContainer"
        :style="`transform: translateX(-${currentPage * 100}vw)`"
        class="flex transition-transform duration-700 ease-out h-full"
        style="width: 400vw;"
    >
        <x-welcome-section />
        <x-menu-section />
        <x-combo-section />
        <x-contact-section />
    </div>

    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-30 flex space-x-3">
        <template x-for="i in maxPages + 1" :key="i">
            <button 
                @click="goToPage(i - 1)" 
                :class="{ 
                    'bg-yellow-400 w-8': currentPage === i - 1, 
                    'bg-white/50 hover:bg-white/75 w-3': currentPage !== i - 1 
                }"
                class="h-3 rounded-full transition-all duration-300 ease-in-out shadow-lg focus:outline-none"
                :aria-label="`Ir a página ${i}`"
            ></button>
        </template>
    </div>

    <button 
        x-show="currentPage > 0"
        @click="prevPage()"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 z-40 p-3 rounded-full bg-black/30 text-white hover:bg-black/70 transition-colors duration-300 hidden md:block"
        aria-label="Página anterior"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <button 
        x-show="currentPage < maxPages"
        @click="nextPage()"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 z-40 p-3 rounded-full bg-black/30 text-white hover:bg-black/70 transition-colors duration-300 hidden md:block"
        aria-label="Página siguiente"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

</div>

<script>
function pagesApp() {
    return {
        currentPage: 0,
        maxPages: 3, // 0-based index for 4 pages
        isScrolling: false,
        startX: 0,
        currentX: 0,
        threshold: 50, // Píxeles mínimos para detectar swipe (suficiente)

        menuContainer: null,

        init() {
            this.currentPage = 0;
            // Configura el contenedor del menú una vez que el DOM esté listo
            this.$nextTick(() => {
                this.menuContainer = this.$refs.menuScroll;
            });
            // Opcional: Agregar soporte para navegación por teclado (flechas)
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    this.nextPage();
                } else if (e.key === 'ArrowLeft') {
                    this.prevPage();
                }
            });
        },

        goToPage(index) {
            if (index >= 0 && index <= this.maxPages) {
                // Previene el doble scroll/swipe
                if (this.isScrolling) return; 
                this.isScrolling = true;
                this.currentPage = index;
                // Ajusta el timeout al mismo tiempo de la transición CSS
                setTimeout(() => {
                    this.isScrolling = false;
                }, 700); 
            }
        },

        nextPage() {
            this.goToPage(this.currentPage + 1);
        },

        prevPage() {
            this.goToPage(this.currentPage - 1);
        },

        // Lógica de scroll/rueda de ratón (funciona bien)
        handleScroll(event) {
            if (this.isScrolling) return;
            
            // Usamos un umbral para evitar que scrolls accidentales pequeños se activen
            const scrollThreshold = 10; 
            
            if (event.deltaY > scrollThreshold) {
                this.nextPage();
            } else if (event.deltaY < -scrollThreshold) {
                this.prevPage();
            }
        },

        handleTouchStart(event) {
            // Verifica si el toque comenzó DENTRO del contenedor de scroll del menú.
            // Si menuContainer existe y el target del evento ES o está DENTRO del contenedor, lo ignoramos.
            if (this.menuContainer && this.menuContainer.contains(event.target)) {
                this.startX = 0; // Marcar como inactivo para evitar swipe de página
                return;
            }

            this.startX = event.touches[0].clientX;
            this.currentX = this.startX;
        },

        handleTouchMove(event) {
            if (this.isScrolling) return;
            // Si startX es 0, el toque comenzó en el área de menú, lo ignoramos Y NO prevenimos default para permitir scroll nativo.
            if (this.startX === 0) return; 
            
            // Solo prevenimos default si estamos manejando un swipe de página (no en menú)
            event.preventDefault();
            this.currentX = event.touches[0].clientX;
        },

        handleTouchEnd(event) {
            if (this.isScrolling || this.startX === 0) return; // Ignorar si ya está en scroll o si se ignoró en touchstart
            
            const deltaX = this.currentX - this.startX;
            
            // Swipe a la DERECHA (deltaX positivo > threshold): Página ANTERIOR (<-)
            if (deltaX > this.threshold) {
                this.prevPage();
            }
            // Swipe a la IZQUIERDA (deltaX negativo < -threshold): Página SIGUIENTE (->)
            else if (deltaX < -this.threshold) {
                this.nextPage();
            }
            
            // Reset
            this.startX = 0;
            this.currentX = 0;
        }
    }
}
</script>
@endsection