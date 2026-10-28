@php $link = 'inline-flex min-h-11 items-center hover:text-rosa'; @endphp

<footer class="relative z-10 mt-16 border-t border-borde bg-fondo">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 text-sm text-texto-suave sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr]">
        <div class="max-w-xs">
            <a href="{{ route('inicio') }}" class="logo inline-flex min-h-11 items-center text-3xl">Venta Dinámica</a>
            <p class="mt-3 leading-relaxed">Tienda online de mangas y cómics, de los primeros tomos a las novelas gráficas clásicas.</p>
        </div>

        <nav aria-labelledby="pie-explorar">
            <h2 id="pie-explorar" class="font-semibold text-texto">Explorar</h2>
            <ul class="mt-1">
                <li><a href="{{ route('inicio') }}#mangas" class="{{ $link }}">Mangas</a></li>
                <li><a href="{{ route('inicio') }}#comics" class="{{ $link }}">Cómics</a></li>
                <li><a href="{{ route('carrito.ver') }}" class="{{ $link }}">Tu carrito</a></li>
            </ul>
        </nav>

        <nav aria-labelledby="pie-tienda">
            <h2 id="pie-tienda" class="font-semibold text-texto">La tienda</h2>
            <ul class="mt-1">
                <li><a href="{{ route('sobre-nosotros') }}" class="{{ $link }}">Sobre nosotros</a></li>
                <li><a href="{{ route('contacto') }}" class="{{ $link }}">Contacto</a></li>
            </ul>
        </nav>
    </div>

    <div class="border-t border-borde">
        <p class="mx-auto max-w-7xl px-4 py-4 text-xs text-texto-suave">
            &copy; 2026 Venta Dinámica. Proyecto de 6.º año de la EPET N.º 20, Neuquén.
        </p>
    </div>
</footer>
