<?php

namespace Tests\Feature;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Inicio, detalle de producto y buscador.
class CatalogoTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_el_inicio_muestra_los_productos_separados_en_mangas_y_comics(): void
    {
        $respuesta = $this->get('/');

        $respuesta->assertOk()
            ->assertSeeInOrder(['MANGAS', 'Naruto Vol. 1', 'CÓMICS', 'Watchmen'])
            ->assertSee('img/portadas/one-piece-1.jpg')
            ->assertSee('$ 14.500')
            ->assertSee('<html lang="es">', false)
            ->assertDontSee('src=""', false)
            ->assertSee('data-lista-productos', false)
            ->assertSee('data-lista-ranking', false)
            ->assertSee('data-token-csrf', false)
            ->assertSee(route('carrito.agregar'), false)
            ->assertDontSee('class="tarjeta producto', false);
    }

    public function test_ranking_orden_y_destacados_del_catalogo(): void
    {
        $respuesta = $this->get('/')->assertOk();
        $primeros = [
            'Hunter x Hunter Vol. 1', 'One-Punch Man Vol. 1',
            'Shingeki no Kyojin Vol. 1',
            "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1", 'Berserk Vol. 1',
        ];
        $this->assertSame($primeros, $respuesta['ranking']->pluck('nombre')->all());
        $this->assertSame($primeros, $respuesta['mangas']->take(5)->pluck('nombre')->all());
        $this->assertSame($primeros, array_column($respuesta['productosReact']['ranking'], 'nombre'));
        $this->assertSame(
            $respuesta['mangas']->pluck('nombre')->all(),
            array_column($respuesta['productosReact']['mangas'], 'nombre'),
        );
        $this->assertSame(
            $respuesta['novedades']->pluck('nombre')->all(),
            array_column($respuesta['productosReact']['novedades'], 'nombre'),
        );
        $this->assertSame([$primeros[0], $primeros[3], $primeros[2]], $respuesta['destacados']->pluck('nombre')->all());
        $this->assertSame(3, substr_count($respuesta->getContent(), 'data-diapositiva'));
        $respuesta->assertDontSee('data-anterior', false)
            ->assertDontSee('data-punto', false)
            ->assertDontSee('data-pausa', false);

        $jojos = $respuesta['mangas']->filter(fn ($p) => str_starts_with($p->nombre, "JoJo's"))->values();
        $this->assertCount(9, $jojos);
        foreach ($jojos as $indice => $producto) {
            $this->assertStringContainsString('Parte '.($indice + 1).':', $producto->nombre);
            $this->assertSame("JoJo's Bizarre Adventure", $producto->serie->nombre);
        }
        $resto = $respuesta['mangas']->slice(13)->pluck('nombre')->all();
        $ordenados = $resto;
        sort($ordenados);
        $this->assertSame($ordenados, $resto);
        // Si cambian las ventas, cambia el ranking: no es una lista fija de la vista.
        $otro = Producto::where('nombre', 'Orient Vol. 1')->firstOrFail();
        Compra::first()->detalles()->create([
            'producto_id' => $otro->id, 'cantidad' => 30,
            'precio_unitario' => $otro->precio, 'total' => $otro->precio * 30,
        ]);
        $this->assertSame($otro->id, $this->get('/')['ranking']->first()->id);
    }

    public function test_repetir_los_seeders_no_duplica_productos_ni_ventas(): void
    {
        $this->seed();
        $this->assertSame(46, Producto::count());
        $this->assertSame(1, Compra::count());
        $this->assertSame(75, (int) DetalleCompra::sum('cantidad'));
    }

    public function test_el_catalogo_pasa_a_react_el_estado_de_stock_sin_exponer_el_inventario_completo(): void
    {
        $respuesta = $this->get('/')->assertOk();
        $productos = array_merge(
            $respuesta['productosReact']['mangas'],
            $respuesta['productosReact']['comics'],
        );
        $sinStock = collect($productos)->firstWhere('nombre', 'Maus I: Mi padre sangra historia');

        $this->assertSame(true, $sinStock['sinStock']);
        $this->assertArrayNotHasKey('stock', $sinStock);
        $this->assertArrayNotHasKey('email', $sinStock);
        $this->assertArrayNotHasKey('password', $sinStock);
    }

    public function test_el_catalogo_escapa_los_datos_antes_de_serializarlos_para_react(): void
    {
        $producto = Producto::where('nombre', 'One Piece Vol. 1')->firstOrFail();
        $producto->update(['nombre' => '</div><script>alert(1)</script>']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('\u003Cscript\u003E', false);
    }

    public function test_el_detalle_muestra_los_datos_del_producto(): void
    {
        $producto = Producto::where('nombre', 'Death Note Vol. 1')->first();

        $this->get(route('productos.detalle', $producto))
            ->assertOk()
            ->assertSee('Death Note Vol. 1')
            ->assertSee('Serie')
            ->assertSee('$ 14.900')
            ->assertSee('quedan 5')
            ->assertSee('Light Yagami');
    }

    public function test_el_detalle_usa_el_slug_y_redirige_las_urls_numericas_anteriores(): void
    {
        $producto = Producto::where('nombre', 'One Piece Vol. 1')->firstOrFail();
        $urlSlug = route('productos.detalle', $producto);

        $this->assertSame(url('/productos/one-piece-vol-1'), $urlSlug);

        $this->get($urlSlug)
            ->assertOk()
            ->assertSee($producto->nombre);

        $this->get('/productos/'.$producto->id)
            ->assertFound()
            ->assertRedirect($urlSlug);
    }

    public function test_un_producto_que_no_existe_da_404(): void
    {
        $this->get('/productos/9999')->assertNotFound();
    }

    public function test_el_buscador_encuentra_por_nombre_y_por_serie(): void
    {
        $this->get('/buscar?q=batman')
            ->assertOk()
            ->assertSee('2 productos encontrados')
            ->assertSee('Batman: Año Uno')
            ->assertSee('Batman: La broma asesina')
            ->assertDontSee('Naruto Vol. 1')
            ->assertSee('data-lista-productos', false)
            ->assertSee('data-mostrar-tipo="true"', false);

        // "Saga" es el nombre de la serie
        $this->get('/buscar?q=saga')->assertSee('Saga Vol. 1');
    }

    public function test_el_buscador_avisa_cuando_no_encuentra_nada(): void
    {
        $this->get('/buscar?q=dragon')
            ->assertOk()
            ->assertSee('No encontramos nada')
            ->assertDontSee('data-lista-productos', false);
    }

    public function test_el_buscador_trata_el_texto_como_texto_y_no_como_sql(): void
    {
        $this->get('/buscar?q='.urlencode("' OR 1=1 --"))
            ->assertOk()
            ->assertSee('0 productos encontrados');
    }

    public function test_el_buscador_no_se_rompe_con_textos_raros(): void
    {
        $this->get('/buscar?q='.str_repeat('a', 500))->assertOk()->assertSee('0 productos encontrados');
        $this->get('/buscar?q[]=naruto')->assertOk()->assertSee('Escribí el nombre');
        $this->get('/buscar')->assertOk()->assertSee('Escribí el nombre');
    }
}
