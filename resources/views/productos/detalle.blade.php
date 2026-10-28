@extends('layouts.app')
@section('titulo', $producto->nombre)
@section('contenido')
    @php $esManga = $producto->tipo === \App\Models\Producto::TIPO_MANGA; @endphp

    <nav aria-label="Ruta de navegación" class="text-sm text-texto-suave">
        <a href="{{ route('inicio') }}" class="inline-flex min-h-11 items-center hover:text-rosa">Catálogo</a>
        <span aria-hidden="true" class="mx-1.5">/</span>
        <a href="{{ route('inicio') }}#{{ $esManga ? 'mangas' : 'comics' }}" class="inline-flex min-h-11 items-center hover:text-rosa">{{ $esManga ? 'Mangas' : 'Cómics' }}</a>
    </nav>

    <article class="panel mt-6 grid min-w-0 gap-8 p-4 sm:p-6 md:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] md:items-start">
        {{-- Portada con el brillo rosa de la marca --}}
        <div class="mx-auto w-full max-w-xs overflow-hidden rounded-xl border border-rosa/60 bg-superficie-2 shadow-neon md:max-w-none">
            @if ($producto->imagen)
                <img src="{{ asset($producto->imagen) }}" alt="Portada de {{ $producto->nombre }}" width="320" height="480" class="h-auto w-full">
            @else
                <p class="flex aspect-[2/3] items-center justify-center p-6 text-center text-texto-suave">Sin portada</p>
            @endif
        </div>

        <div>
            <span class="badge border border-borde text-texto-suave">{{ $esManga ? 'Manga' : 'Cómic' }}</span>
            <h1 class="titulo-seccion mt-3 text-4xl leading-tight md:text-5xl">{{ $producto->nombre }}</h1>

            <dl class="mt-6 grid max-w-md grid-cols-[auto_1fr] gap-x-6 gap-y-2 text-sm">
                @if ($producto->serie)
                    <dt class="text-texto-suave">Serie</dt>
                    <dd>{{ $producto->serie->nombre }} @if ($producto->serie->estado) <span class="text-texto-suave">({{ strtolower($producto->serie->estado) }})</span> @endif</dd>
                @endif
                @if ($producto->numero)
                    <dt class="text-texto-suave">Tomo</dt>
                    <dd>{{ $producto->numero }}@if ($producto->serie?->cant_tomos) de {{ $producto->serie->cant_tomos }}@endif</dd>
                @endif
                @if ($producto->numero_paginas)
                    <dt class="text-texto-suave">Páginas</dt>
                    <dd>{{ $producto->numero_paginas }}</dd>
                @endif
            </dl>

            <div class="mt-8 max-w-md rounded-xl border border-borde bg-superficie p-5">
                <p class="text-4xl font-bold">{{ $producto->precioFormateado() }}</p>

                @if ($producto->stock > 0)
                    <p class="mt-1 text-sm {{ $producto->stock <= 3 ? 'text-rosa' : 'text-exito' }}">
                        {{ $producto->stock <= 3 ? 'Últimas unidades: ' : 'En stock: ' }}{{ $producto->stock === 1 ? 'queda 1' : "quedan {$producto->stock}" }}
                    </p>
                    <form action="{{ route('carrito.agregar') }}" method="post" class="mt-5 flex flex-wrap items-end gap-3">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <div>
                            <label for="cantidad" class="etiqueta">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="campo w-24">
                        </div>
                        <button type="submit" class="btn btn-primario flex-1">Añadir al carrito</button>
                    </form>
                @else
                    <p class="mt-1 text-sm text-error">Sin stock por el momento.</p>
                    <a href="{{ route('inicio') }}#{{ $esManga ? 'mangas' : 'comics' }}" class="btn btn-secundario mt-5 w-full">Ver otros {{ $esManga ? 'mangas' : 'cómics' }}</a>
                @endif
            </div>

            <h2 class="mt-10 text-lg font-semibold">De qué se trata</h2>
            <p class="mt-2 max-w-prose leading-relaxed text-texto-suave">{{ $producto->descripcion }}</p>
        </div>
    </article>
@endsection
