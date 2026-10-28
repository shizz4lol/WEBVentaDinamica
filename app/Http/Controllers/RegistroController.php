<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegistroController extends Controller
{
    public function formulario()
    {
        return view('registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'nombre_usuario' => ['required', 'alpha_dash:ascii', 'min:3', 'max:30', 'unique:clientes,nombre_usuario'],
            'email' => ['required', 'email', 'max:255', 'unique:clientes,email'],
            // confirmed: tiene que coincidir con el campo "password_confirmation"
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'nombre.required' => 'Escribí tu nombre.',
            'nombre_usuario.required' => 'Elegí un nombre de usuario.',
            'nombre_usuario.alpha_dash' => 'El usuario solo puede tener letras sin tilde, números, guiones y guiones bajos.',
            'nombre_usuario.min' => 'El usuario tiene que tener al menos 3 caracteres.',
            'nombre_usuario.unique' => 'Ese nombre de usuario ya está en uso.',
            'email.required' => 'Escribí tu email.',
            'email.email' => 'Escribí un email válido.',
            'email.unique' => 'Ya hay una cuenta con ese email.',
            'password.required' => 'Elegí una contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña tiene que tener al menos 8 caracteres.',
            '*.max' => 'Es demasiado largo.',
        ]);

        // La contraseña se guarda hasheada solita por el cast 'hashed' del modelo Cliente.
        $cliente = Cliente::create($datos);

        // Login automático después de registrarse.
        Auth::guard('web')->login($cliente);
        Carrito::fusionarSesion($cliente, $request->session());
        $request->session()->regenerate();

        return redirect()->route('inicio')->with('estado', "¡Bienvenida/o, {$cliente->nombre}! Tu cuenta ya está creada.");
    }
}
