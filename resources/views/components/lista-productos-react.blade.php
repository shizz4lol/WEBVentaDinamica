@props([
    'productos',
    'mostrarTipo' => false,
    'mensajeVacio' => 'Todavía no hay productos cargados.',
])

<div
    {{ $attributes }}
    data-lista-productos
    data-productos="{{ \Illuminate\Support\Js::encode($productos) }}"
    data-token-csrf="{{ csrf_token() }}"
    data-url-carrito="{{ route('carrito.agregar') }}"
    data-mostrar-tipo="{{ $mostrarTipo ? 'true' : 'false' }}"
    data-mensaje-vacio="{{ $mensajeVacio }}"
></div>
