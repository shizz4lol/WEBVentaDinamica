@extends('layouts.app')
@section('titulo', 'Iniciar sesión')
@section('contenido')
    <div class="mx-auto max-w-md">
        <h1 class="titulo-seccion text-center">INICIAR SESIÓN</h1>

        <form method="POST" action="{{ route('login.ingresar') }}" class="tarjeta mt-6 space-y-5 p-6" novalidate>
            @csrf

            <fieldset>
                <legend class="etiqueta">Entrar como</legend>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="cliente" class="accent-rosa" @checked(old('tipo', 'cliente') === 'cliente')>
                        Cliente
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="admin" class="accent-rosa" @checked(old('tipo') === 'admin')>
                        Administrador
                    </label>
                </div>
                @error('tipo') <p class="error-campo">{{ $message }}</p> @enderror
            </fieldset>

            <div>
                <label for="nombre_usuario" class="etiqueta">Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}"
                       class="campo" autocomplete="username" required
                       @error('nombre_usuario') aria-invalid="true" aria-describedby="error-usuario" @enderror>
                @error('nombre_usuario') <p id="error-usuario" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="etiqueta">Contraseña</label>
                <input type="password" id="password" name="password" class="campo" autocomplete="current-password" required
                       @error('password') aria-invalid="true" aria-describedby="error-password" @enderror>
                @error('password') <p id="error-password" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="recordarme" value="1" class="accent-rosa">
                Recordarme en esta computadora
            </label>

            <button type="submit" class="btn btn-primario w-full">Ingresar</button>
        </form>
        <p class="mt-4 text-center text-sm text-texto-suave">
            ¿No tenés cuenta? <a href="{{ route('registro') }}" class="enlace">Creá una acá</a>
        </p>
    </div>
@endsection
