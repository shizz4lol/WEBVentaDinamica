<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

// ---- Catálogo (público) ----
Route::get('/', [CatalogoController::class, 'inicio'])->name('inicio');
Route::get('/productos/{producto}', [CatalogoController::class, 'detalle'])->name('productos.detalle');
Route::get('/buscar', [CatalogoController::class, 'buscar'])->name('buscar');

Route::view('/sobre-nosotros', 'extra.sobre-nosotros')->name('sobre-nosotros');
Route::get('/contacto', [ContactoController::class, 'formulario'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->middleware('throttle:contacto')->name('contacto.enviar');

// ---- Login (solo si todavía no iniciaste sesión) ----
Route::middleware('guest:web,admin')->group(function () {
    Route::get('/login', [LoginController::class, 'formulario'])->name('login');
    // throttle:login = como máximo 5 intentos por minuto (los límites están en AppServiceProvider).
    Route::post('/login', [LoginController::class, 'ingresar'])->middleware('throttle:login')->name('login.ingresar');

    Route::get('/registro', [RegistroController::class, 'formulario'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'registrar'])->middleware('throttle:registro')->name('registro.guardar');
});
Route::post('/logout', [LoginController::class, 'salir'])->name('logout');

// ---- Carrito (clientes e invitados) ----
Route::get('/carrito', [CarritoController::class, 'ver'])->name('carrito.ver');
Route::post('/carrito', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::delete('/carrito/invitado/{productoId}', [CarritoController::class, 'quitarInvitado'])
    ->whereNumber('productoId')
    ->name('carrito.quitar-invitado');
Route::middleware('auth:web')->group(function () {
    Route::delete('/carrito/{item}', [CarritoController::class, 'quitar'])->name('carrito.quitar');
});

// ---- Administración (solo administradores) ----
Route::name('admin.')->middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'panel'])->name('panel');
    Route::get('/series/buscar', [AdminController::class, 'buscarSeries'])->name('series.buscar');
    Route::resource('productos', AdminController::class)->except(['show']);
});
