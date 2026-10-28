@extends('layouts.app')
@section('titulo', 'Tu carrito')
@section('contenido')
    <h1 class="titulo-seccion">TU CARRITO</h1>

    @guest('web')
        <p class="mt-4 text-sm text-texto-suave"><a href="{{ route('login') }}" class="hover:text-rosa underline">Iniciá sesión</a> para guardar tu carrito.</p>
    @endguest

    @if ($carrito->items->isEmpty())
        {{-- Estado vacío: un globo de diálogo, como en las viñetas --}}
        <div class="mt-8 max-w-lg">
            <div class="globo">
                <p class="font-titulo text-3xl tracking-wide">¡Acá no hay nada todavía!</p>
                <p class="mt-2 text-texto-suave">Tu carrito está vacío. Elegí un tomo del catálogo y aparece acá.</p>
            </div>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('inicio') }}#mangas" class="btn btn-primario">Ver mangas</a>
                <a href="{{ route('inicio') }}#comics" class="btn btn-secundario">Ver cómics</a>
            </div>
        </div>
    @else
        <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">
            <ul class="space-y-3">
                @foreach ($carrito->items as $item)
                    @php $producto = $item->producto; @endphp
                    {{-- flex-wrap: en el celular el subtotal y "Quitar" bajan a otra línea en vez de salirse de la pantalla --}}
                    <li class="tarjeta flex flex-wrap items-center gap-x-4 gap-y-3">
                        @if ($producto->imagen)
                            <img src="{{ asset($producto->imagen) }}" alt="Portada de {{ $producto->nombre }}" width="64" height="96" class="h-24 w-16 flex-none rounded border border-rosa/40 object-cover">
                        @endif

                        <div class="min-w-0 basis-40 flex-1">
                            <a href="{{ route('productos.detalle', $producto) }}" class="inline-flex min-h-11 items-center font-semibold hover:text-rosa">{{ $producto->nombre }}</a>
                            <p class="text-sm text-texto-suave">{{ $item->cantidad }} × {{ $producto->precioFormateado() }}</p>
                            @if ($item->cantidad > $producto->stock)
                                <p class="text-sm text-error">Ahora quedan {{ $producto->stock }}: ajustá la cantidad antes de comprar.</p>
                            @endif
                        </div>

                        <div class="ml-auto flex items-center gap-3">
                            <p class="font-bold whitespace-nowrap">$ {{ number_format($item->cantidad * $producto->precio, 0, ',', '.') }}</p>

                            <form action="{{ auth('web')->check() ? route('carrito.quitar', $item) : route('carrito.quitar-invitado', $producto) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secundario px-3">
                                    Quitar <span class="sr-only">{{ $producto->nombre }}</span>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Resumen: en pantallas grandes queda a la derecha y acompaña al bajar --}}
            <aside aria-labelledby="titulo-resumen" class="rounded-xl border border-rosa/50 bg-superficie p-5 lg:sticky lg:top-6">
                <h2 id="titulo-resumen" class="font-semibold">Resumen</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-texto-suave">Productos</dt>
                        <dd>{{ $carrito->cantidadDeProductos() }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between border-t border-borde pt-3 text-xl">
                        <dt>Total</dt>
                        <dd class="font-bold" data-total>$ {{ number_format($carrito->total(), 0, ',', '.') }}</dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs leading-relaxed text-texto-suave">El pago online todavía no está disponible: por ahora el carrito guarda lo que elegiste.</p>
                <a href="{{ route('inicio') }}" class="btn btn-secundario mt-4 w-full">Seguir mirando</a>
            </aside>
        </div>
    @endif
@endsection
