{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<div 
    x-data="pagesApp()" 
    x-init="init()"
    class="w-full h-screen overflow-hidden relative"
    @wheel.prevent.debounce.150ms="handleScroll($event)"
>
    <!-- Pages Container -->
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

</div>

<script>
function pagesApp() {
    return {
        currentPage: 0,
        maxPages: 3, // 0-based index for 4 pages
        isScrolling: false,

        init() {
            // Asegurar que empezamos en la página 0
            this.currentPage = 0;
        },

        goToPage(index) {
            if (index >= 0 && index <= this.maxPages) {
                this.currentPage = index;
            }
        },

        nextPage() {
            if (this.currentPage < this.maxPages && !this.isScrolling) {
                this.isScrolling = true;
                this.currentPage++;
                setTimeout(() => {
                    this.isScrolling = false;
                }, 700); // Duración de la transición
            }
        },

        prevPage() {
            if (this.currentPage > 0 && !this.isScrolling) {
                this.isScrolling = true;
                this.currentPage--;
                setTimeout(() => {
                    this.isScrolling = false;
                }, 700);
            }
        },

        handleScroll(event) {
            if (this.isScrolling) return;
            
            if (event.deltaY > 0) {
                this.nextPage();
            } else if (event.deltaY < 0) {
                this.prevPage();
            }
        }
    }
}
</script>
@endsection