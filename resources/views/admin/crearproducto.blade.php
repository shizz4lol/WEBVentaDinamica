@extends('layouts.app')
@section('titulo', 'Crear producto')
@section('contenido')
    <div class="mb-6">
        <a href="{{ route('admin.productos.index') }}" class="enlace text-sm">← Volver a productos</a>
        <h1 class="titulo-seccion mt-3">CREAR PRODUCTO</h1>
    </div>

    @include('admin.formulario-producto', ['producto' => null, 'serieSeleccionada' => $serieSeleccionada])
@endsection
