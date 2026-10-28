@extends('layouts.app')
@section('titulo', 'Contacto')
@section('contenido')
    <div class="mx-auto max-w-xl">
        <h1 class="titulo-seccion text-center">¡CONTACTANOS!</h1>

        <form method="POST" action="{{ route('contacto.enviar') }}" class="tarjeta mt-6 space-y-5 p-6" novalidate>
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="nombre" class="etiqueta">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Pepito" class="campo" autocomplete="given-name" required>
                    @error('nombre') <p class="error-campo">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="apellido" class="etiqueta">Apellido</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" placeholder="Pérez" class="campo" autocomplete="family-name" required>
                    @error('apellido') <p class="error-campo">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="correo" class="etiqueta">Correo electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" placeholder="pepito@example.com" class="campo" autocomplete="email" required>
                @error('correo') <p class="error-campo">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="asunto" class="etiqueta">Asunto</label>
                <input type="text" id="asunto" name="asunto" value="{{ old('asunto') }}" placeholder="Consulta" class="campo" required>
                @error('asunto') <p class="error-campo">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="mensaje" class="etiqueta">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribí tu mensaje acá..." class="campo" required>{{ old('mensaje') }}</textarea>
                @error('mensaje') <p class="error-campo">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn btn-primario w-full">Enviar</button>
        </form>
    </div>
@endsection
