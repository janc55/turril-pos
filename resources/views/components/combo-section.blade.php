<!-- Page 3: Combos -->
<section class="w-[100vw] h-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-black via-black/50 to-yellow-900/20 relative overflow-hidden">
    <div class="container mx-auto h-full px-4 py-16 flex flex-col items-center justify-center">
        <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-8 text-center">Combos Especiales</h2>
        <p class="text-white/70 mb-12 text-center max-w-lg">Combina tu sandwich favorito con una gaseosa para una experiencia completa.</p>
        
        @php
            $comboDir = public_path('images/combos');
            $comboImages = [];
            if (is_dir($comboDir)) {
                $files = scandir($comboDir);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $comboImages[] = $file;
                    }
                }
            }
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full max-w-4xl">
            @foreach($comboImages as $image)
                <div class="group bg-black/20 rounded-lg overflow-hidden border border-yellow-400/20 hover:border-yellow-400/50 transition-all duration-300 transform hover:scale-105">
                    <img src="{{ asset('images/combos/' . $image) }}" alt="Combo {{ basename($image, '.' . pathinfo($image, PATHINFO_EXTENSION)) }}" class="w-full h-80 object-cover">
                </div>
            @endforeach
        </div>
        
        <button @click="nextPage()" class="mt-8 text-yellow-400 hover:text-white transition-colors text-lg">→ Contáctanos</button>
    </div>
</section>