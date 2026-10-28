<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactoTest extends TestCase
{
    public function test_el_formulario_valida_y_agradece(): void
    {
        $this->post('/contacto', [])->assertSessionHasErrors(['nombre', 'apellido', 'correo', 'asunto', 'mensaje']);

        $this->post('/contacto', [
            'nombre' => 'Pepito', 'apellido' => 'Pérez', 'correo' => 'pepito@example.com',
            'asunto' => 'Consulta', 'mensaje' => '¿Tienen el tomo 2?',
        ])->assertRedirect(route('contacto'))->assertSessionHas('estado');
    }
}
