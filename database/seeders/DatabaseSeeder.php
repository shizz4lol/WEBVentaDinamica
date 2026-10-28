<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // El orden importa: los productos necesitan que existan las series.
        $this->call([
            AdministradorSeeder::class,
            ClienteSeeder::class,
            SerieSeeder::class,
            ProductoSeeder::class,
            CompraSeeder::class,
        ]);
    }
}
