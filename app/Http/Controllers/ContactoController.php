<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function formulario()
    {
        return view('extra.contacto');
    }

    // Valida el formulario. Por ahora NO manda un mail: eso queda para más adelante.
    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'email', 'max:255'],
            'asunto' => ['required', 'string', 'max:150'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ], [
            '*.required' => 'Este campo es obligatorio.',
            'correo.email' => 'Escribí un correo válido.',
            '*.max' => 'Es demasiado largo.',
        ]);

        return redirect()->route('contacto')->with('estado', '¡Gracias por escribirnos! Te vamos a responder pronto.');
    }
}
