<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function formulario()
    {
        return view('login');
    }

    public function ingresar(Request $request)
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:cliente,admin'],
            'nombre_usuario' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'tipo.required' => 'Elegí si entrás como cliente o como administrador.',
            'tipo.in' => 'Elegí si entrás como cliente o como administrador.',
            'nombre_usuario.required' => 'Escribí tu usuario.',
            'password.required' => 'Escribí tu contraseña.',
        ]);

        // "web" es el guard de clientes y "admin" el de administradores (config/auth.php).
        $guard = $datos['tipo'] === 'admin' ? 'admin' : 'web';
        $credenciales = [
            'nombre_usuario' => $datos['nombre_usuario'],
            'password' => $datos['password'],
        ];

        if (! Auth::guard($guard)->attempt($credenciales, $request->boolean('recordarme'))) {
            // Mismo mensaje si falla el usuario o la contraseña: así no se revela qué usuarios existen.
            return back()
                ->withErrors(['nombre_usuario' => 'Usuario o contraseña incorrectos.'])
                ->onlyInput('nombre_usuario', 'tipo');
        }

        if ($guard === 'web') {
            Carrito::fusionarSesion(Auth::guard('web')->user(), $request->session());
        }

        // Nueva sesión después de loguearse (evita el robo de sesión por "session fixation").
        $request->session()->regenerate();

        return $guard === 'admin'
            ? redirect()->route('admin.panel')
            : redirect()->intended(route('inicio'));
    }

    public function salir(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
