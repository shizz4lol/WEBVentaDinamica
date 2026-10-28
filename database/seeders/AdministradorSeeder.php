<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Seeder;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario de PRUEBA. La contraseña se guarda hasheada por el cast del modelo.
        Administrador::updateOrCreate(
            ['nombre_usuario' => 'admin'],
            ['nombre' => 'Administradora', 'password' => 'admin1234'],
        );
    }
}
