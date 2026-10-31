@extends('layouts.app')
@section('titulo', 'Buscar')
@section('contenido')
    @php $sugerencias = ['One Piece', 'Batman', 'Saga']; @endphp

    @if ($texto === '')
        <h1 class="titulo-seccion">BUSCAR</h1>
        <p class="mt-3 text-texto-suave">Escribí el nombre de un manga, un cómic o una serie en el buscador.</p>
    @else
        <h1 class="titulo-seccion">Resultados para “{{ $texto }}”</h1>
        <p class="mt-2 text-texto-suave">
            {{ $resultados->count() === 1 ? '1 producto encontrado' : $resultados->count().' productos encontrados' }}
        </p>

        @if ($resultados->isEmpty())
            <div class="mt-8 max-w-lg">
                <div class="globo">
                    <p class="font-titulo text-3xl tracking-wide">¿Y eso dónde está?</p>
                    <p class="mt-2 text-texto-suave">No encontramos nada con “{{ $texto }}”. Revisá cómo está escrito o probá con otra serie.</p>
                </div>
                <p class="mt-8 text-sm text-texto-suave">Probá con:</p>
                <ul class="mt-2 flex flex-wrap gap-2">
                    @foreach ($sugerencias as $sugerencia)
                        <li><a href="{{ route('buscar', ['q' => $sugerencia]) }}" class="btn btn-secundario">{{ $sugerencia }}</a></li>
                    @endforeach
                </ul>
            </div>
        @else
            <x-lista-productos-react
                :productos="$productosReact"
                :mostrar-tipo="true"
                mensaje-vacio="No encontramos productos."
                class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5"
            />
        @endif
    @endif
@endsection
