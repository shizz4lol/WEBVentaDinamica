<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Serie;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Conserva el producto anterior y sus relaciones al normalizar el nombre.
        Producto::where('nombre', "JoJo's Bizarre Adventure (Parte 1: Phantom Blood) Vol. 1")
            ->update(['nombre' => "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1"]);

        // Las portadas están en public/img/portadas/. Precios y stock de ejemplo.
        $productos = [
            // ---- MANGAS ----
            [
                'nombre' => 'One Piece Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'One Piece',
                'numero' => 1,
                'numero_paginas' => 208,
                'precio' => 14500,
                'stock' => 12,
                'imagen' => 'img/portadas/one-piece-1.jpg',
                'descripcion' => "Monkey D. Luffy sueña con ser el Rey de los Piratas y encontrar el legendario One Piece.\nCon su sombrero de paja, sale al mar en busca de aventuras y compañeros.",
            ],
            [
                'nombre' => 'Naruto Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Naruto',
                'numero' => 1,
                'numero_paginas' => 192,
                'precio' => 13900,
                'stock' => 8,
                'imagen' => 'img/portadas/naruto-1.jpg',
                'descripcion' => "Naruto Uzumaki es un aprendiz de ninja que busca el reconocimiento de su aldea.\nCon esfuerzo y mucha energía, sueña con convertirse en Hokage.",
            ],
            [
                'nombre' => 'Death Note Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Death Note',
                'numero' => 1,
                'numero_paginas' => 200,
                'precio' => 14900,
                'stock' => 5,
                'imagen' => 'img/portadas/death-note-1.jpg',
                'descripcion' => 'Light Yagami encuentra un cuaderno que mata a cualquiera cuyo nombre se escriba en él. Así empieza un duelo de ingenio con el misterioso detective L.',
            ],
            [
                'nombre' => 'Chainsaw Man Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Chainsaw Man',
                'numero' => 1,
                'numero_paginas' => 192,
                'precio' => 15500,
                'stock' => 10,
                'imagen' => 'img/portadas/chainsaw-man-1.jpg',
                'descripcion' => "Denji sobrevive cazando demonios junto a su compañero Pochita.\nAcción y humor negro se cruzan en su búsqueda de una vida mejor.",
            ],
            [
                'nombre' => 'Spy x Family Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Spy x Family',
                'numero' => 1,
                'numero_paginas' => 216,
                'precio' => 15900,
                'stock' => 3,
                'imagen' => 'img/portadas/spy-x-family-1.jpg',
                'descripcion' => "Un espía necesita formar una familia para llevar adelante una misión secreta.\nUna comedia de acción donde cada integrante guarda algo inesperado.",
            ],
            [
                'nombre' => 'Hunter x Hunter Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Hunter x Hunter',
                'numero' => 1,
                'numero_paginas' => 184,
                'precio' => 14900,
                'stock' => 9,
                'imagen' => 'img/portadas/hunter-x-hunter-1.jpg',
                'descripcion' => "Gon deja su isla para seguir los pasos de su padre y convertirse en Hunter.\nEl examen de ingreso lo acerca a nuevos amigos y desafíos impredecibles.",
            ],
            [
                'nombre' => 'One-Punch Man Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'One-Punch Man',
                'numero' => 1,
                'numero_paginas' => 200,
                'precio' => 15500,
                'stock' => 11,
                'imagen' => 'img/portadas/one-punch-man-1.jpg',
                'descripcion' => "Saitama vence a cualquier enemigo de un solo golpe y busca un desafío que lo entusiasme.\nYusuke Murata adapta la historia de ONE con acción espectacular y humor absurdo.",
            ],
            [
                'nombre' => 'Shingeki no Kyojin Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'serie' => 'Shingeki no Kyojin',
                'numero' => 1,
                'numero_paginas' => 192,
                'precio' => 15900,
                'stock' => 7,
                'imagen' => 'img/portadas/shingeki-no-kyojin-1.jpg',
                'descripcion' => "La humanidad se refugia tras enormes murallas para protegerse de los titanes.\nEren sueña con conocer el mundo exterior en una historia de supervivencia y misterio.",
            ],
            // ---- CÓMICS ----
            [
                'nombre' => 'Watchmen',
                'tipo' => Producto::TIPO_COMIC,
                'serie' => null,
                'numero' => null,
                'numero_paginas' => 448,
                'precio' => 54900,
                'stock' => 4,
                'imagen' => 'img/portadas/watchmen.jpg',
                'descripcion' => 'Alan Moore y Dave Gibbons reinventan a los superhéroes en una historia de misterio ambientada en plena Guerra Fría.',
            ],
            [
                'nombre' => 'Batman: Año Uno',
                'tipo' => Producto::TIPO_COMIC,
                'serie' => null,
                'numero' => null,
                'numero_paginas' => 144,
                'precio' => 32900,
                'stock' => 6,
                'imagen' => 'img/portadas/batman-year-one.jpg',
                'descripcion' => 'Frank Miller y David Mazzucchelli cuentan el primer año de Bruce Wayne como Batman y la llegada de James Gordon a Gotham.',
            ],
            [
                'nombre' => 'Maus I: Mi padre sangra historia',
                'tipo' => Producto::TIPO_COMIC,
                'serie' => null,
                'numero' => 1,
                'numero_paginas' => 160,
                'precio' => 38500,
                'stock' => 0,
                'imagen' => 'img/portadas/maus-1.jpg',
                'descripcion' => 'Art Spiegelman narra la historia de su padre, sobreviviente del Holocausto. Ganadora del Premio Pulitzer.',
            ],
            [
                'nombre' => 'Saga Vol. 1',
                'tipo' => Producto::TIPO_COMIC,
                'serie' => 'Saga',
                'numero' => 1,
                'numero_paginas' => 160,
                'precio' => 35900,
                'stock' => 7,
                'imagen' => 'img/portadas/saga-1.jpg',
                'descripcion' => 'Space opera de Brian K. Vaughan y Fiona Staples: dos soldados de bandos enemigos se enamoran y huyen con su hija recién nacida.',
            ],
            [
                'nombre' => 'Batman: La broma asesina',
                'tipo' => Producto::TIPO_COMIC,
                'serie' => null,
                'numero' => null,
                'numero_paginas' => 64,
                'precio' => 29900,
                'stock' => 9,
                'imagen' => 'img/portadas/the-killing-joke.jpg',
                'descripcion' => 'Alan Moore y Brian Bolland exploran el origen del Joker y su enfrentamiento más personal con Batman.',
            ],
        ];

        // Tomos iniciales adicionales. Páginas aproximadas según la edición de referencia.
        // Precios en ARS y stock de ejemplo para el catálogo académico.
        $mangasAdicionales = [
            [
                'serie' => 'Jujutsu Kaisen', 'slug' => 'jujutsu-kaisen-1',
                'numero_paginas' => 192, 'precio' => 15500, 'stock' => 10,
                'descripcion' => "Yuji Itadori se acerca al peligroso mundo de las maldiciones.\nUna aventura de hechicería y acción donde cada decisión tiene un costo.",
            ],
            [
                'serie' => 'Re:Zero (manga)', 'slug' => 're-zero-1',
                'numero_paginas' => 160, 'precio' => 14900, 'stock' => 6,
                'descripcion' => "Subaru aparece en un mundo fantástico sin saber cómo llegó.\nEl primer arco del manga, Un día en la capital, abre su misteriosa aventura.",
            ],
            [
                'serie' => 'Dr. STONE', 'slug' => 'dr-stone-1',
                'numero_paginas' => 192, 'precio' => 14500, 'stock' => 8,
                'descripcion' => "Tras una extraña petrificación global, Senku despierta en un mundo sin tecnología.\nSu ingenio científico será la clave para reconstruir la civilización.",
            ],
            [
                'serie' => 'KONOSUBA (manga)', 'slug' => 'konosuba-1',
                'numero_paginas' => 168, 'precio' => 13900, 'stock' => 7,
                'descripcion' => "Kazuma recibe la oportunidad de empezar otra vida en un mundo de fantasía.\nLa diosa Aqua lo acompaña en una aventura llena de magia y desastres cómicos.",
            ],
            [
                'serie' => 'Solo Leveling', 'slug' => 'solo-leveling-1',
                'numero_paginas' => 320, 'precio' => 18000, 'stock' => 9,
                'descripcion' => "Sung Jinwoo arriesga su vida explorando mazmorras para sostener a su familia.\nEste manhwa a color presenta un mundo de cazadores, monstruos y desafíos imposibles.",
            ],
            [
                'serie' => 'Dorohedoro', 'slug' => 'dorohedoro-1',
                'numero_paginas' => 168, 'precio' => 16900, 'stock' => 5,
                'descripcion' => "Caiman busca al hechicero que le dio una cabeza de reptil y borró sus recuerdos.\nJunto a Nikaido recorre una ciudad donde la magia y el humor negro conviven.",
            ],
            [
                'serie' => 'Frieren', 'slug' => 'frieren-1',
                'numero_paginas' => 192, 'precio' => 15900, 'stock' => 12,
                'descripcion' => "La elfa Frieren inicia un viaje después de la gran aventura de su grupo de héroes.\nUna fantasía sobre el tiempo, los recuerdos y el valor de conocer a los demás.",
            ],
            [
                'serie' => 'Tonari no Kaibutsu-kun (My Little Monster)', 'slug' => 'tonari-no-kaibutsu-kun-1',
                'numero_paginas' => 208, 'precio' => 13900, 'stock' => 4,
                'descripcion' => "Shizuku solo piensa en estudiar hasta que conoce al impredecible Haru.\nDos adolescentes muy distintos descubren la amistad en esta comedia romántica escolar.",
            ],
            [
                'serie' => 'Kaiju No. 8', 'slug' => 'kaiju-no-8-1',
                'numero_paginas' => 208, 'precio' => 15500, 'stock' => 11,
                'descripcion' => "Kafka limpia los restos de los monstruos que atacan Japón y sueña con combatirlos.\nUna nueva oportunidad lo acerca a la Fuerza de Defensa de una forma inesperada.",
            ],
            [
                'serie' => 'DAN DA DAN', 'slug' => 'dan-da-dan-1',
                'numero_paginas' => 208, 'precio' => 15900, 'stock' => 10,
                'descripcion' => "Momo cree en fantasmas y Okarun en extraterrestres: ambos quieren demostrar su teoría.\nSu apuesta abre una aventura paranormal de acción, humor y encuentros insólitos.",
            ],
            [
                'serie' => 'Berserk', 'slug' => 'berserk-1',
                'numero_paginas' => 224, 'precio' => 17900, 'stock' => 6,
                'descripcion' => "Guts es un espadachín errante que recorre un mundo medieval lleno de amenazas.\nEl inicio de una fantasía oscura de Kentaro Miura, dirigida a lectores adultos.",
            ],
            [
                'serie' => 'Demon Slayer (Kimetsu no Yaiba)', 'slug' => 'demon-slayer-1',
                'numero_paginas' => 192, 'precio' => 14500, 'stock' => 13,
                'descripcion' => "Tanjiro vive con su familia en las montañas de un Japón acechado por demonios.\nSu determinación y su vínculo con Nezuko marcan el comienzo de un largo viaje.",
            ],
            [
                'serie' => "Masamune-kun's Revenge", 'slug' => 'masamune-kuns-revenge-1',
                'numero_paginas' => 180, 'precio' => 13500, 'stock' => 5,
                'descripcion' => "Masamune vuelve transformado y prepara un plan para impresionar a Aki.\nSu deseo de revancha se enreda con los sentimientos en esta comedia romántica.",
            ],
            [
                'serie' => 'Kaguya-sama: Love is War', 'slug' => 'kaguya-sama-love-is-war-1',
                'numero_paginas' => 216, 'precio' => 14900, 'stock' => 8,
                'descripcion' => "Kaguya y Miyuki se gustan, pero su orgullo les impide confesarlo.\nEl consejo estudiantil se vuelve un campo de estrategias para que el otro dé el primer paso.",
            ],
            [
                'serie' => 'Fate/Zero (manga)', 'slug' => 'fate-zero-1',
                'numero_paginas' => 194, 'precio' => 15900, 'stock' => 4,
                'descripcion' => "Siete magos invocan héroes legendarios para competir por el Santo Grial.\nLa adaptación al manga presenta una contienda de ideales y magia en Fuyuki.",
            ],
            [
                'serie' => 'Overlord (manga)', 'slug' => 'overlord-1',
                'numero_paginas' => 180, 'precio' => 15500, 'stock' => 7,
                'descripcion' => "Momonga espera el cierre de su juego favorito dentro de su fortaleza virtual.\nCuando llega la hora, su avatar esquelético se encuentra ante una realidad desconocida.",
            ],
            [
                'serie' => 'FLCL (manga)', 'slug' => 'flcl-1',
                'numero_paginas' => 192, 'precio' => 12900, 'stock' => 3,
                'descripcion' => "La rutina de Naota cambia con la llegada de Haruko y su guitarra.\nHajime Ueda reinterpreta FLCL en un manga de ciencia ficción, caos y adolescencia.",
            ],
            [
                'serie' => 'Mob Psycho 100', 'slug' => 'mob-psycho-100-1',
                'numero_paginas' => 200, 'precio' => 14500, 'stock' => 9,
                'descripcion' => "Shigeo, apodado Mob, quiere vivir como cualquier estudiante pese a sus poderes psíquicos.\nEntre exorcismos y problemas cotidianos, intenta comprender sus emociones.",
            ],
            [
                'serie' => "JoJo's Bizarre Adventure",
                'nombre' => "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1",
                'slug' => 'jojos-bizarre-adventure-phantom-blood-1',
                'numero_paginas' => 256, 'precio' => 17900, 'stock' => 8,
                'descripcion' => "En la Inglaterra victoriana, Jonathan Joestar conoce a su nuevo hermano adoptivo, Dio.\nLa rivalidad y una misteriosa máscara abren la primera parte de la saga JoJo.",
            ],
            ['serie' => 'Orient', 'slug' => 'orient-1', 'numero_paginas' => 208, 'precio' => 14500, 'stock' => 10, 'descripcion' => 'Musashi sueña con convertirse en bushi junto a su amigo Kojiro, en un Japón dominado por misteriosos seres.'],
            ['serie' => 'Fairy Tail', 'slug' => 'fairy-tail-1', 'numero_paginas' => 208, 'precio' => 15000, 'stock' => 12, 'descripcion' => 'Lucy quiere unirse al gremio de magos Fairy Tail. Su encuentro con Natsu abre una aventura de magia y amistad.'],
            ['serie' => 'Tower of God — Torre de Dios', 'slug' => 'tower-of-god-1', 'numero_paginas' => 288, 'precio' => 18000, 'stock' => 7, 'descripcion' => 'Bam entra en una torre misteriosa para seguir a Rachel. Un manhwa a color de pruebas, alianzas y aventuras.'],
            ['serie' => 'Fullmetal Alchemist', 'slug' => 'fullmetal-alchemist-1', 'numero_paginas' => 192, 'precio' => 16000, 'stock' => 9, 'descripcion' => 'Los hermanos Edward y Alphonse Elric recorren un mundo de alquimia buscando respuestas sobre sus cuerpos.'],
            ['serie' => 'Boku no Hero Academia (My Hero Academia)', 'slug' => 'boku-no-hero-academia-1', 'numero_paginas' => 192, 'precio' => 15500, 'stock' => 15, 'descripcion' => 'Izuku sueña con ser héroe en una sociedad de superpoderes. Su admiración por All Might lo impulsa a intentarlo.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 2: Battle Tendency Vol. 1", 'slug' => 'jojo-parte-2-1', 'numero_paginas' => 272, 'precio' => 17900, 'stock' => 5, 'descripcion' => 'Joseph Joestar enfrenta extraños peligros en una aventura que mezcla ingenio y acción.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 3: Stardust Crusaders Vol. 1", 'slug' => 'jojo-parte-3-1', 'numero_paginas' => 320, 'precio' => 17900, 'stock' => 6, 'descripcion' => 'Jotaro descubre un poder llamado Stand y emprende un viaje junto a su abuelo Joseph.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 4: Diamond is Unbreakable Vol. 1", 'slug' => 'jojo-parte-4-1', 'numero_paginas' => 376, 'precio' => 17900, 'stock' => 7, 'descripcion' => 'Josuke Higashikata descubre que la tranquila Morioh esconde usuarios de poderes extraordinarios.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 5: Golden Wind Vol. 1", 'slug' => 'jojo-parte-5-1', 'numero_paginas' => 392, 'precio' => 17900, 'stock' => 8, 'descripcion' => 'Giorno Giovanna persigue su sueño en una Italia atravesada por organizaciones criminales y Stands.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 6: Stone Ocean Vol. 1", 'slug' => 'jojo-parte-6-1', 'numero_paginas' => 376, 'precio' => 17900, 'stock' => 9, 'descripcion' => 'Jolyne Cujoh debe adaptarse a la vida en prisión mientras descubre un poder desconocido.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 7: Steel Ball Run Vol. 1", 'slug' => 'jojo-parte-7-1', 'numero_paginas' => 392, 'precio' => 17900, 'stock' => 10, 'descripcion' => 'Johnny Joestar y Gyro Zeppeli participan en una carrera a caballo a través de Estados Unidos.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 8: JoJolion Vol. 1", 'slug' => 'jojo-parte-8-1', 'numero_paginas' => 232, 'precio' => 17900, 'stock' => 11, 'descripcion' => 'Un joven sin recuerdos busca su identidad en una Morioh transformada por fenómenos inexplicables.'],
            ['serie' => "JoJo's Bizarre Adventure", 'nombre' => "JoJo's Bizarre Adventure Parte 9: The JOJOLands Vol. 1", 'slug' => 'jojo-parte-9-1', 'numero_paginas' => 216, 'precio' => 17900, 'stock' => 12, 'descripcion' => 'Jodio Joestar y sus compañeros persiguen la fortuna en Hawái, entre planes arriesgados y poderes extraños.'],
        ];

        foreach ($mangasAdicionales as $manga) {
            $slug = $manga['slug'];
            unset($manga['slug']);
            $productos[] = [
                'nombre' => $manga['nombre'] ?? $manga['serie'].' Vol. 1',
                'tipo' => Producto::TIPO_MANGA,
                'numero' => 1,
                'imagen' => is_file(public_path("img/portadas/{$slug}.jpg"))
                    ? "img/portadas/{$slug}.jpg"
                    : null,
                ...$manga,
            ];
        }

        // El webcomic original de ONE (2009, publicado en internet). La imagen es un dibujo de ONE
        // tomado de la wiki en español de One-Punch Man (Fandom), para uso académico.
        $productos[] = [
            'nombre' => 'One-Punch Man: el webcomic original de ONE',
            'tipo' => Producto::TIPO_COMIC,
            'serie' => 'One-Punch Man',
            'numero' => null,
            'numero_paginas' => null,
            'precio' => 18900,
            'stock' => 5,
            'imagen' => 'img/portadas/one-punch-man-webcomic.jpg',
            'descripcion' => 'La historia de Saitama tal como la dibujó ONE en su webcomic, antes de la versión de Yusuke Murata: trazo simple, mucho humor y el mismo héroe que gana de una piña.',
        ];

        $ordenDestacado = [
            'Hunter x Hunter Vol. 1' => 1,
            'One-Punch Man Vol. 1' => 2,
            'Shingeki no Kyojin Vol. 1' => 3,
            "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1" => 4,
            'Berserk Vol. 1' => 5,
        ];

        foreach (["Battle Tendency", "Stardust Crusaders", "Diamond is Unbreakable", "Golden Wind", "Stone Ocean", "Steel Ball Run", "JoJolion", "The JOJOLands"] as $indice => $parte) {
            $numero = $indice + 2;
            $ordenDestacado["JoJo's Bizarre Adventure Parte {$numero}: {$parte} Vol. 1"] = $numero + 4;
        }

        foreach ($productos as $datos) {
            $serie = $datos['serie'] ? Serie::where('nombre', $datos['serie'])->first() : null;
            unset($datos['serie']);
            $datos['serie_id'] = $serie?->id;
            // Restaura también a null el orden del webcomic y de los demás productos.
            $datos['orden'] = $ordenDestacado[$datos['nombre']] ?? null;

            Producto::updateOrCreate(['nombre' => $datos['nombre']], $datos);
        }
    }
}
