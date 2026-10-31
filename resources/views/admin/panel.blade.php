@extends('layouts.app')
@section('titulo', 'Panel de administración')
@section('contenido')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="titulo-seccion">PANEL DE ADMINISTRACIÓN</h1>
            <p class="mt-2 text-texto-suave">Hola, {{ auth('admin')->user()->nombre }}. Administrá el catálogo de productos.</p>
        </div>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primario">Crear producto</a>
    </div>

    @error('producto')
        <p role="alert" class="mt-6 rounded-lg border border-error/40 bg-error/10 px-4 py-3 text-error">{{ $message }}</p>
    @enderror

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
                    <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-borde">
                @forelse ($productos as $producto)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $producto->nombre }}</td>
                        <td class="px-4 py-3">{{ $producto->tipo === \App\Models\Producto::TIPO_MANGA ? 'Manga' : 'Cómic' }}</td>
                        <td class="px-4 py-3 text-texto-suave">{{ $producto->serie?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $producto->precioFormateado() }}</td>
                        <td class="px-4 py-3 text-right {{ $producto->stock === 0 ? 'text-error' : '' }}">{{ $producto->stock }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-secundario">Editar</a>
                                <div
                                    data-modal-confirmacion
                                    data-accion="{{ route('admin.productos.destroy', $producto) }}"
                                    data-token-csrf="{{ csrf_token() }}"
                                    data-identificador="{{ $producto->id }}"
                                    data-titulo="Borrar producto"
                                    data-mensaje="¿Querés borrar {{ $producto->nombre }}? Esta acción no se puede deshacer."
                                    data-texto-boton="Borrar producto"
                                ></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-texto-suave">Todavía no hay productos cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
