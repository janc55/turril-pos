<!-- Page 4: Contacto y Pedido -->
<section class="w-[100vw] h-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-black via-black/50 to-yellow-900/20 relative overflow-hidden">
    <div class="text-center px-4 max-w-md">
        <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-8">¡Haz tu Pedido Ahora!</h2>
        <p class="text-white/80 mb-6">Un click y llega a tu puerta. O visítanos en persona.</p>
        
        <!-- Dirección y Horarios -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-6">
            <p class="text-white/90 mb-2"><strong>📍 Dirección:</strong> Calle Ayacucho entre 6 de octubre y Soria Galvarro</p>
            <p class="text-white/90"><strong>🕒 Horarios:</strong> Lun - Vie: 10:00-13:00 y 16:30-20:30 | Sáb: 11:00-15:00</p>
        </div>
        
        <div class="space-y-4 mb-8">
            <a href="tel:+59177150404" class="block bg-yellow-400 text-black py-3 rounded font-medium hover:bg-yellow-300 transition-colors">📞 Llamar: +591 77150404</a>
            <a href="https://wa.me/59177150404?text=Hola%2C%20quiero%20hacer%20un%20pedido%20en%20tu%20restaurante." class="block bg-green-500 text-white py-3 rounded font-medium hover:bg-green-600 transition-colors">💬 WhatsApp Pedido</a>
        </div>
        
        <!-- Redes Sociales Minimalistas -->
        <div class="flex justify-center items-center space-x-6 mb-8">
            <a href="https://www.facebook.com/profile.php?id=61554767464875" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-center w-10 h-10">
                <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-8 h-8 opacity-70 group-hover:opacity-100 transition-opacity filter invert(92%) sepia(1) saturate(200%) hue-rotate(20deg) brightness(110%) contrast(100%)" />
            </a>
            <a href="https://www.instagram.com/elturril.oruro/" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-center w-10 h-10">
                <img src="{{ asset('images/instagram.svg') }}" alt="Instagram" class="w-6 h-6 opacity-70 group-hover:opacity-100 transition-opacity filter invert(92%) sepia(1) saturate(200%) hue-rotate(20deg) brightness(110%) contrast(100%)" />
            </a>
            <a href="#" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-center w-10 h-10">
                <img src="{{ asset('images/tiktok.svg') }}" alt="TikTok" class="w-6 h-6 opacity-70 group-hover:opacity-100 transition-opacity filter invert(92%) sepia(1) saturate(200%) hue-rotate(20deg) brightness(110%) contrast(100%)" />
            </a>
        </div>
        
        <button @click="prevPage()" class="mt-8 text-white/50 hover:text-yellow-400 transition-colors text-sm">← Volver al Menú</button>
    </div>
</section>