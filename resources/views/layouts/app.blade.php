<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icono.png') }}">
    <title>@yield('titulo', 'Mangas y cómics') · Venta Dinámica</title>
    <meta name="description" content="Venta Dinámica: tienda online de mangas y cómics.">
    {{-- Vite: carga Tailwind (app.css) y React (app.jsx). viteReactRefresh va primero. --}}
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido" class="sr-only rounded-lg bg-rosa px-4 py-2 font-semibold text-fondo focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:inline-flex focus:min-h-11 focus:items-center">
        Saltar al contenido
    </a>

    @include('layouts.header')

    <main id="contenido" class="relative z-0 mx-auto w-full min-w-0 max-w-7xl flex-1 px-4 py-8 lg:py-10">
        @include('layouts.mensajes')
        @yield('contenido')
    </main>

    @include('layouts.footer')
</body>
</html>
