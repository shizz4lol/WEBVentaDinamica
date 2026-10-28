@extends('layouts.app')
@section('titulo', 'Panel de administración')
@section('contenido')
    <h1 class="titulo-seccion">PANEL DE ADMINISTRACIÓN</h1>
    <p class="mt-2 text-texto-suave">
        Hola, {{ auth('admin')->user()->nombre }}. Por ahora esta lista es solo de lectura:
        crear, editar y borrar productos es la próxima tarea.
    </p>

    <div class="panel mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Productos cargados</caption>
            <thead class="bg-superficie-2 text-texto-suave">
                <tr>
                    <th scope="col" class="px-4 py-3">Producto</th>
                    <th scope="col" class="px-4 py-3">Tipo</th>
                    <th scope="col" class="px-4 py-3">Serie</th>
                    <th scope="col" class="px-4 py-3 text-right">Precio</th>
                    <th scope="col" class="px-4 py-3 text-right">Stock</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-borde">
                @foreach ($productos as $producto)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $producto->nombre }}</td>
                        <td class="px-4 py-3">{{ $producto->tipo === \App\Models\Producto::TIPO_MANGA ? 'Manga' : 'Cómic' }}</td>
                        <td class="px-4 py-3 text-texto-suave">{{ $producto->serie?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $producto->precioFormateado() }}</td>
                        <td class="px-4 py-3 text-right {{ $producto->stock === 0 ? 'text-error' : '' }}">{{ $producto->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
