{{-- Mensajes que dejan los controladores con ->with('estado', ...) o ->withErrors(['carrito' => ...]) --}}
@if (session('estado'))
    <p role="status" class="mb-6 rounded-lg border border-exito/40 bg-exito/10 px-4 py-3 text-exito">
        {{ session('estado') }}
    </p>
@endif

@error('carrito')
    <p role="alert" class="mb-6 rounded-lg border border-error/40 bg-error/10 px-4 py-3 text-error">
        {{ $message }}
    </p>
@enderror
