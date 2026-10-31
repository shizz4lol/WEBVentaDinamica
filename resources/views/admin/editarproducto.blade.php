@extends('layouts.app')
@section('titulo', 'Editar producto')
@section('contenido')
    <div class="mb-6">
        <a href="{{ route('admin.productos.index') }}" class="enlace text-sm">← Volver a productos</a>
        <h1 class="titulo-seccion mt-3">EDITAR PRODUCTO</h1>
        <p class="mt-2 text-texto-suave">{{ $producto->nombre }}</p>
    </div>

    @include('admin.formulario-producto', ['producto' => $producto, 'serieSeleccionada' => $serieSeleccionada])
@endsection
