{{-- Tarjeta de un producto. Uso: <x-tarjeta-producto :producto="$producto" />
     Con :mostrar-tipo="true" muestra si es manga o cómic (útil en la búsqueda, donde se mezclan). --}}
@props(['producto', 'mostrarTipo' => false])

@php
    $sinStock = $producto->stock === 0;
    $pocasUnidades = $producto->stock > 0 && $producto->stock <= 3;
    $esNuevo = $producto->created_at?->gte(now()->subDays(30)) ?? false;
@endphp

<article class="tarjeta producto group flex min-w-0 flex-col hover:border-rosa">
    <a href="{{ route('productos.detalle', $producto) }}" class="relative block aspect-[2/3] overflow-hidden rounded-lg bg-superficie-2" aria-label="Ver {{ $producto->nombre }}">
        @if ($producto->imagen)
            <img src="{{ asset($producto->imagen) }}" alt="Portada de {{ $producto->nombre }}" width="320" height="480" loading="lazy"
                 @class(['h-full w-full object-cover', 'opacity-50 grayscale' => $sinStock])>
        @else
            <span class="flex h-full items-center justify-center p-4 text-center text-texto-suave">{{ $producto->nombre }}</span>
        @endif
        @if ($pocasUnidades)
            <span class="badge absolute top-2 left-2 w-auto bg-rosa text-fondo">Últimas unidades</span>
        @elseif ($esNuevo)
            <span class="badge absolute top-2 left-2 w-auto bg-rosa text-fondo">Nuevo</span>
        @endif
    </a>

    <div class="mt-3 flex flex-1 flex-col">
        {{-- Badges: solo cuando dan información (tipo en la búsqueda, stock bajo) --}}
        @if ($mostrarTipo)
            <div class="mb-2 flex flex-wrap gap-1.5">
                @if ($mostrarTipo)
                    <span class="badge border border-borde text-texto-suave">{{ $producto->tipo === \App\Models\Producto::TIPO_MANGA ? 'Manga' : 'Cómic' }}</span>
                @endif

            </div>
        @endif

        <h3 class="font-semibold leading-snug -my-1">
            <a href="{{ route('productos.detalle', $producto) }}" class="flex min-h-11 items-center hover:text-rosa">{{ $producto->nombre }}</a>
        </h3>
        @if ($producto->serie)
            <p class="mt-0.5 text-sm text-texto-suave">{{ $producto->serie->nombre }}</p>
        @endif

        @if ($producto->numero)
            <p class="mt-1 text-sm text-texto-suave">Tomo {{ $producto->numero }}</p>
        @endif

        <p class="mt-auto pt-3 text-lg font-bold">{{ $producto->precioFormateado() }}</p>

        {{-- Una sola acción por tarjeta: añadir al carrito --}}
        @if (! $sinStock)
            <form action="{{ route('carrito.agregar') }}" method="post" class="mt-2">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <button type="submit" class="btn btn-primario w-full">
                    Añadir al carrito
                    <span class="sr-only">: {{ $producto->nombre }}</span>
                </button>
            </form>
        @else
            <button type="button" class="btn mt-2 w-full" disabled>Sin stock</button>
        @endif
    </div>
</article>
