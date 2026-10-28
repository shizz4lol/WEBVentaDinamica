<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Límites de intentos por minuto. Cada uno tiene nombre propio para que no compartan
        // el contador: si no, 5 mensajes de contacto bloqueaban el login (y al revés).
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('contacto', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('registro', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        // El header recibe la cantidad del cliente o de la sesión invitada.
        View::composer('layouts.header', function ($view) {
            $cliente = Auth::guard('web')->user();
            $carrito = $cliente?->carrito()->with('items')->first();
            $cantidadInvitado = Auth::guard('admin')->check() ? 0 : array_sum(session('carrito', []));

            $view->with('cantidadCarrito', $cliente ? ($carrito?->cantidadDeProductos() ?? 0) : $cantidadInvitado);
        });
    }
}
