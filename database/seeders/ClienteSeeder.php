<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario de PRUEBA con datos inventados.
        Cliente::updateOrCreate(
            ['nombre_usuario' => 'cliente'],
            [
                'password' => 'cliente1234',
                'nombre' => 'Lucía',
                'apellido' => 'Gómez',
                'dni' => '40111222',
                'email' => 'cliente@example.com',
                'telefono' => '2994123456',
                'nombre_calle' => 'Av. Argentina',
                'numero_calle' => 1200,
                'codigo_postal' => '8300',
                'localidad' => 'Neuquén',
            ],
        );
    }
}
