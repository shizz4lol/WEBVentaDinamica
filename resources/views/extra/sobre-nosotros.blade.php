@extends('layouts.app')
@section('titulo', 'Sobre nosotros')
@section('contenido')
    <article class="panel mx-auto max-w-2xl p-5 sm:p-8">
        <h1 class="titulo-seccion">SOBRE NOSOTROS</h1>

        <div class="mt-6 space-y-4 leading-relaxed text-texto-suave">
            <p>
                <strong class="text-texto">Venta Dinámica</strong> es una tienda online de cómics y mangas:
                desde los primeros tomos de las series más leídas hasta las novelas gráficas que marcaron la historia del cómic.
            </p>
            <p>
                ¿Tenés alguna consulta o sugerencia? Escribinos desde la página de
                <a href="{{ route('contacto') }}" class="enlace">contacto</a>.
            </p>
        </div>
    </article>
@endsection
