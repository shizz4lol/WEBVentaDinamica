@php
    $cliente = auth('web')->user();
    $admin = auth('admin')->user();
    // Clases de cada link del menú. min-h-11 = 44 px de alto (cómodo para tocar con el dedo).
    // aria-current="page" marca en rosa la página en la que estás.
    $link = 'flex min-h-11 items-center rounded px-1 hover:text-rosa aria-[current=page]:text-rosa';
@endphp

<header class="site-header relative z-20 border-b border-borde bg-fondo">
    <div class="header-interior mx-auto flex max-w-7xl flex-wrap items-center gap-x-2 gap-y-3 px-4 py-3 sm:gap-x-4">

        {{-- Botón del menú: solo se ve en el celular y la tablet --}}
        <button id="menu-boton" type="button" class="flex min-h-11 min-w-11 items-center justify-center rounded-lg text-2xl leading-none xl:hidden"
                aria-expanded="false" aria-controls="menu-principal">
            <span aria-hidden="true">&#9776;</span>
            <span class="sr-only">Abrir o cerrar el menú</span>
        </button>

        <a href="{{ route('inicio') }}" class="flex min-h-11 min-w-0 max-w-[calc(100%-7rem)] items-center sm:max-w-none">
            <img src="{{ asset('img/logo.png') }}" alt="Venta Dinámica" width="2172" height="724" class="h-auto max-h-16 w-44 object-contain sm:w-52 xl:w-44">
        </a>

        {{-- Carrito: en el celular queda arriba a la derecha --}}
        @unless ($admin)
            <a href="{{ route('carrito.ver') }}" @if (request()->routeIs('carrito.*')) aria-current="page" @endif
               class="relative ml-auto flex min-h-11 min-w-11 items-center justify-center rounded-lg hover:text-rosa aria-[current=page]:text-rosa xl:order-4 xl:ml-0">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="sr-only">Ver carrito</span>
                {{-- Acá React monta el componente ContadorCarrito (resources/js/app.jsx) --}}
                <span id="contador-carrito" data-cantidad="{{ $cantidadCarrito }}"></span>
            </a>
        @endunless

        {{-- Buscador: formulario GET, la búsqueda queda en la URL (/buscar?q=...) --}}
        <form action="{{ route('buscar') }}" method="get" role="search" class="relative order-last w-full xl:order-3 xl:ml-0 xl:w-64 xl:shrink-0">
            <label for="buscador" class="sr-only">Buscar productos</label>
            {{-- is_string: si alguien manda ?q[]=x en la URL, no se rompe la página --}}
            <input type="search" id="buscador" name="q" value="{{ is_string(request('q')) ? request('q') : '' }}" maxlength="100"
                   placeholder="Buscar manga o cómic..." autocomplete="off"
                   class="campo pr-12">
            <button type="submit" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-texto-suave hover:text-rosa">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span class="sr-only">Buscar</span>
            </button>
        </form>

        <nav id="menu-principal" aria-label="Principal"
             class="hidden w-full flex-col xl:order-2 xl:flex xl:min-w-0 xl:flex-1 xl:flex-row xl:flex-wrap xl:items-center xl:gap-x-3">
            <a href="{{ route('inicio') }}" class="{{ $link }}" @if (request()->routeIs('inicio')) aria-current="page" @endif>Inicio</a>
            <a href="{{ route('inicio').'#novedades' }}" class="{{ $link }}">Novedades</a>
            <a href="{{ route('inicio').'#ranking' }}" class="{{ $link }}">Ranking</a>
            <a href="{{ route('inicio').'#mangas' }}" class="{{ $link }}">Mangas</a>
            <a href="{{ route('inicio').'#comics' }}" class="{{ $link }}">Cómics</a>
            <a href="{{ route('sobre-nosotros') }}" class="{{ $link }}" @if (request()->routeIs('sobre-nosotros')) aria-current="page" @endif>Sobre nosotros</a>

            @if ($admin)
                <a href="{{ route('admin.panel') }}" class="{{ $link }}" @if (request()->routeIs('admin.*')) aria-current="page" @endif>Panel admin</a>
            @endif

            @if ($cliente || $admin)
                <span class="flex min-h-11 items-center px-1 text-texto-suave">Hola, {{ ($cliente ?? $admin)->nombre }}</span>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="{{ $link }}">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ $link }}" @if (request()->routeIs('login')) aria-current="page" @endif>Iniciar sesión</a>
                <a href="{{ route('registro') }}" @if (request()->routeIs('registro')) aria-current="page" @endif
                   class="flex min-h-11 items-center self-start rounded-lg border border-rosa/70 px-3 text-rosa hover:bg-rosa hover:text-fondo xl:self-auto">Crear cuenta</a>
            @endif
        </nav>
    </div>
</header>
