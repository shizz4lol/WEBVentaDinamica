@extends('layouts.app')
@section('titulo', 'Inicio')
@section('contenido')
    <div class="collage-portadas" aria-hidden="true">
        <div class="collage-grilla">
            @foreach ($portadas as $portada)
                @if ($portada)
                    <img src="{{ asset($portada) }}" alt="" width="240" height="360">
                @endif
            @endforeach
        </div>
    </div>

    <h1 class="sr-only">Venta Dinámica: mangas y cómics para tu colección</h1>
    @if ($destacados->isNotEmpty())
        @php
            $banners = [
                'Hunter x Hunter Vol. 1' => ['hunter-x-hunter', 'Hunter x Hunter', 'Tu aventura como Hunter empieza acá.', 'Más vendido'],
                "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1" => ['jojos', "JoJo’s Bizarre Adventure", 'Una rivalidad. Generaciones de aventuras.', 'Destacado'],
                'Shingeki no Kyojin Vol. 1' => ['shingeki-no-kyojin', 'Shingeki no Kyojin', 'Descubrí qué hay más allá de las murallas.', 'Destacado'],
            ];
        @endphp
        <section data-carrusel class="banners" role="region" aria-roledescription="carrusel" aria-label="Mangas destacados" tabindex="0">
            <div class="banners-ventana">
                <div data-pista class="banners-pista">
                    @foreach ($destacados as $destacado)
                        @php ([$slug, $titulo, $gancho, $badge] = $banners[$destacado->nombre])
                        <article data-diapositiva class="banner-slide" aria-roledescription="diapositiva" aria-label="{{ $loop->iteration }} de {{ $destacados->count() }}: {{ $titulo }}">
                            <a href="{{ route('productos.detalle', $destacado) }}" class="banner" aria-label="{{ $destacado->nombre }}: {{ $gancho }}">
                                <img class="banner-fondo" src="{{ asset('img/banners/'.$slug.'.jpg') }}" alt="" width="600" height="900">
                                <div class="banner-degradado" aria-hidden="true"></div>
                                <img class="banner-portada" src="{{ asset($destacado->imagen) }}" alt="" width="240" height="360">
                                <div class="banner-texto">
                                    <span class="banner-badge">{{ $badge }}</span>
                                    <h2>{{ $titulo }}</h2>
                                    <p>{{ $gancho }}</p>
                                    <span class="banner-cta">Vol. 1 · Descubrilo <span aria-hidden="true">↗</span></span>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="grid min-w-0 gap-8 lg:grid-cols-[minmax(0,1fr)_17rem]">
        <div class="min-w-0">
            <section id="novedades" aria-labelledby="titulo-novedades" class="scroll-mt-6">
                <div class="cabecera-seccion"><h2 id="titulo-novedades" class="titulo-seccion">Novedades</h2></div>
                <x-lista-productos-react
                    :productos="$productosReact['novedades']"
                    mensaje-vacio="Todavía no hay novedades cargadas."
                    class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5"
                />
            </section>
        </div>

        <aside class="min-w-0 space-y-6" aria-label="Ranking">
            <section id="ranking" aria-labelledby="titulo-ranking" class="panel scroll-mt-6 p-4">
                <h2 id="titulo-ranking" class="font-titulo text-2xl tracking-wide">Más vendidos</h2>
                <ol
                    data-lista-ranking
                    data-productos="{{ \Illuminate\Support\Js::encode($productosReact['ranking']) }}"
                    data-mensaje-vacio="Todavía no hay productos en el ranking."
                    class="mt-4 divide-y divide-borde"
                >
                </ol>
            </section>

        </aside>
    </div>

    @foreach ([['mangas', 'MANGAS', $mangas], ['comics', 'CÓMICS', $comics]] as [$id, $titulo, $productos])
        <section id="{{ $id }}" aria-labelledby="titulo-{{ $id }}" class="mt-12 scroll-mt-6">
            <div class="cabecera-seccion">
                <h2 id="titulo-{{ $id }}" class="titulo-seccion">{{ $titulo }}</h2>
                <p class="text-sm text-texto-suave">{{ $productos->count() }} {{ $productos->count() === 1 ? 'título' : 'títulos' }}</p>
            </div>
            <x-lista-productos-react
                :productos="$productosReact[$id]"
                mensaje-vacio="Todavía no hay {{ strtolower($titulo) }} cargados."
                class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5"
            />
        </section>
    @endforeach
    <section aria-labelledby="titulo-redes" class="panel mt-12 p-5">
        <h2 id="titulo-redes" class="font-titulo text-2xl tracking-wide">Redes</h2>
        <div class="my-4 flex gap-4 text-rosa" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M14 21v-8h3l.5-3H14V8c0-1 .5-1.5 1.5-1.5H18V4h-2.5C12 4 11 6 11 8v2H8v3h3v8"/></svg>
        </div>
        <p class="text-sm leading-relaxed text-texto-suave">Próximamente en Instagram y Facebook</p>
    </section>
@endsection
