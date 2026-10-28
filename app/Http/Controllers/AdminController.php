<?php

namespace App\Http\Controllers;

use App\Models\Producto;

// Solo para administradores (middleware auth:admin en routes/web.php).
// El CRUD de productos (crear, editar, borrar) es la próxima tarea de Ámbar.
class AdminController extends Controller
{
    public function panel()
    {
        return view('admin.panel', [
            'productos' => Producto::with('serie')->orderBy('tipo')->orderBy('nombre')->get(),
        ]);
    }
}
