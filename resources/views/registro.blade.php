@extends('layouts.app')
@section('titulo', 'Crear cuenta')
@section('contenido')
    <div class="mx-auto max-w-md">
        <h1 class="titulo-seccion text-center">CREAR CUENTA</h1>

        <form method="POST" action="{{ route('registro.guardar') }}" class="tarjeta mt-6 space-y-5 p-6" novalidate>
            @csrf

            <div>
                <label for="nombre" class="etiqueta">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="campo" autocomplete="given-name" required
                       @error('nombre') aria-invalid="true" aria-describedby="error-nombre" @enderror>
                @error('nombre') <p id="error-nombre" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nombre_usuario" class="etiqueta">Nombre de usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" class="campo" autocomplete="username" required
                       aria-describedby="ayuda-usuario @error('nombre_usuario') error-usuario @enderror" @error('nombre_usuario') aria-invalid="true" @enderror>
                <p id="ayuda-usuario" class="mt-1 text-sm text-texto-suave">Con este usuario vas a iniciar sesión.</p>
                @error('nombre_usuario') <p id="error-usuario" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="etiqueta">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="campo" autocomplete="email" required
                       @error('email') aria-invalid="true" aria-describedby="error-email" @enderror>
                @error('email') <p id="error-email" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="etiqueta">Contraseña</label>
                <input type="password" id="password" name="password" class="campo" autocomplete="new-password" required
                       aria-describedby="ayuda-password @error('password') error-password @enderror" @error('password') aria-invalid="true" @enderror>
                <p id="ayuda-password" class="mt-1 text-sm text-texto-suave">Mínimo 8 caracteres.</p>
                @error('password') <p id="error-password" class="error-campo">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="etiqueta">Repetí la contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="campo" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-primario w-full">Crear cuenta</button>
        </form>

        <p class="mt-4 text-center text-sm text-texto-suave">
            ¿Ya tenés cuenta? <a href="{{ route('login') }}" class="enlace">Iniciá sesión</a>
        </p>
    </div>
@endsection
