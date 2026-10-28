<?php

namespace Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Serie::where('nombre', "JoJo's Bizarre Adventure (Parte 1: Phantom Blood)")
            ->update(['nombre' => "JoJo's Bizarre Adventure"]);

        $series = [
            ['nombre' => 'One Piece', 'estado' => 'En emisión', 'cant_tomos' => 110, 'demografia' => 'Shonen'],
            ['nombre' => 'Naruto', 'estado' => 'Finalizada', 'cant_tomos' => 72, 'demografia' => 'Shonen'],
            ['nombre' => 'Death Note', 'estado' => 'Finalizada', 'cant_tomos' => 12, 'demografia' => 'Shonen'],
            ['nombre' => 'Chainsaw Man', 'estado' => 'En emisión', 'cant_tomos' => 20, 'demografia' => 'Shonen'],
            ['nombre' => 'Spy x Family', 'estado' => 'En emisión', 'cant_tomos' => 15, 'demografia' => 'Shonen'],
            ['nombre' => 'Saga', 'estado' => 'En emisión', 'cant_tomos' => 11, 'demografia' => null],
            ['nombre' => 'Hunter x Hunter', 'estado' => 'En pausa', 'cant_tomos' => 38, 'demografia' => 'Shonen'],
            ['nombre' => 'One-Punch Man', 'estado' => 'En emisión', 'cant_tomos' => 34, 'demografia' => 'Seinen'],
            ['nombre' => 'Shingeki no Kyojin', 'estado' => 'Finalizada', 'cant_tomos' => 34, 'demografia' => 'Shonen'],
            ['nombre' => 'Jujutsu Kaisen', 'estado' => 'Finalizada', 'cant_tomos' => 30, 'demografia' => 'Shonen'],
            // Primer arco del manga: Un día en la capital (no la novela ligera).
            ['nombre' => 'Re:Zero (manga)', 'estado' => 'Finalizada', 'cant_tomos' => 2, 'demografia' => 'Seinen'],
            ['nombre' => 'Dr. STONE', 'estado' => 'Finalizada', 'cant_tomos' => 27, 'demografia' => 'Shonen'],
            ['nombre' => 'KONOSUBA (manga)', 'estado' => 'En emisión', 'cant_tomos' => 23, 'demografia' => 'Shonen'],
            // El manhwa coreano no tiene una demografía editorial japonesa.
            ['nombre' => 'Solo Leveling', 'estado' => 'Finalizada', 'cant_tomos' => null, 'demografia' => null],
            ['nombre' => 'Dorohedoro', 'estado' => 'Finalizada', 'cant_tomos' => 23, 'demografia' => 'Seinen / Shonen'],
            ['nombre' => 'Frieren', 'estado' => 'En pausa', 'cant_tomos' => 15, 'demografia' => 'Shonen'],
            ['nombre' => 'Tonari no Kaibutsu-kun (My Little Monster)', 'estado' => 'Finalizada', 'cant_tomos' => 13, 'demografia' => 'Shojo'],
            ['nombre' => 'Kaiju No. 8', 'estado' => 'Finalizada', 'cant_tomos' => 16, 'demografia' => 'Shonen'],
            ['nombre' => 'DAN DA DAN', 'estado' => 'En emisión', 'cant_tomos' => 25, 'demografia' => 'Shonen'],
            ['nombre' => 'Berserk', 'estado' => 'En emisión', 'cant_tomos' => 43, 'demografia' => 'Seinen'],
            ['nombre' => 'Demon Slayer (Kimetsu no Yaiba)', 'estado' => 'Finalizada', 'cant_tomos' => 23, 'demografia' => 'Shonen'],
            ['nombre' => "Masamune-kun's Revenge", 'estado' => 'Finalizada', 'cant_tomos' => 11, 'demografia' => 'Shonen'],
            ['nombre' => 'Kaguya-sama: Love is War', 'estado' => 'Finalizada', 'cant_tomos' => 28, 'demografia' => 'Seinen'],
            ['nombre' => 'Fate/Zero (manga)', 'estado' => 'Finalizada', 'cant_tomos' => 14, 'demografia' => 'Seinen'],
            // La adaptación de Hugin Miyama finalizó; New World es otra serie.
            ['nombre' => 'Overlord (manga)', 'estado' => 'Finalizada', 'cant_tomos' => 19, 'demografia' => 'Seinen'],
            ['nombre' => 'FLCL (manga)', 'estado' => 'Finalizada', 'cant_tomos' => 2, 'demografia' => 'Seinen'],
            ['nombre' => 'Mob Psycho 100', 'estado' => 'Finalizada', 'cant_tomos' => 16, 'demografia' => 'Shonen'],
            ['nombre' => "JoJo's Bizarre Adventure", 'estado' => 'En emisión', 'cant_tomos' => null, 'demografia' => 'Shonen / Seinen'],
            ['nombre' => 'Orient', 'estado' => 'Finalizada', 'cant_tomos' => 22, 'demografia' => 'Shonen'],
            ['nombre' => 'Fairy Tail', 'estado' => 'Finalizada', 'cant_tomos' => 63, 'demografia' => 'Shonen'],
            ['nombre' => 'Tower of God — Torre de Dios', 'estado' => 'En emisión', 'cant_tomos' => null, 'demografia' => null],
            ['nombre' => 'Fullmetal Alchemist', 'estado' => 'Finalizada', 'cant_tomos' => 27, 'demografia' => 'Shonen'],
            ['nombre' => 'Boku no Hero Academia (My Hero Academia)', 'estado' => 'Finalizada', 'cant_tomos' => 42, 'demografia' => 'Shonen'],
        ];

        foreach ($series as $serie) {
            Serie::updateOrCreate(['nombre' => $serie['nombre']], $serie);
        }
    }
}
