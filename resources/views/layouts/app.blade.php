{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="7ic8FbjjldcFHhnzUnseqJyllGN_yUOaZcdu8jPn1f8" />
    <title>El Turril - Sandwiches Ahumados</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Favicon básico -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_elturril.svg') }}">
</head>
<body class="bg-black text-white">

    <main class="min-h-screen">
        @yield('content')
    </main>
    
</body>
</html>