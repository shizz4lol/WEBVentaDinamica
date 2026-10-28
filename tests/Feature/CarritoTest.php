<?php

namespace Tests\Feature;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function cliente(): Cliente
    {
        return Cliente::first();
    }

    private function producto(string $nombre): Producto
    {
        return Producto::where('nombre', $nombre)->firstOrFail();
    }

    public function test_invitado_puede_agregar_ver_y_quitar(): void
    {
        $producto = $this->producto('One Piece Vol. 1');
        $this->get('/carrito')->assertOk()->assertSee('Tu carrito está vacío');
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])->assertSessionHasNoErrors();
        $this->post('/carrito', ['producto_id' => $producto->id])->assertSessionHas('carrito', [$producto->id => 3]);
        $this->get('/carrito')->assertOk()->assertSee($producto->nombre)->assertSee('$ 43.500')
            ->assertSee('para guardar tu carrito')->assertSee(route('carrito.quitar-invitado', $producto), false);
        $this->assertDatabaseCount('carritos', 0);
        $this->delete(route('carrito.quitar-invitado', $producto))->assertRedirect(route('carrito.ver'))->assertSessionHas('carrito', []);
        $this->get('/carrito')->assertSee('Tu carrito está vacío');
    }

    public function test_invitado_no_supera_stock_y_valida_datos(): void
    {
        $producto = $this->producto('Spy x Family Vol. 1');
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])->assertSessionHasNoErrors();
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])->assertSessionHasErrors('carrito')
            ->assertSessionHas('carrito', [$producto->id => 2]);
        $sinStock = $this->producto('Maus I: Mi padre sangra historia');
        $this->post('/carrito', ['producto_id' => $sinStock->id])->assertSessionHasErrors('carrito');
        foreach ([0, -1, 100, 'muchos'] as $cantidad) {
            $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => $cantidad])->assertSessionHasErrors('cantidad');
        }
        $this->post('/carrito', ['producto_id' => 999999])->assertSessionHasErrors('producto_id');
    }

    public function test_fusion_al_iniciar_sesion_suma_y_recorta_al_stock(): void
    {
        $producto = $this->producto('Spy x Family Vol. 1');
        $otro = $this->producto('One Piece Vol. 1');
        $sinStock = $this->producto('Maus I: Mi padre sangra historia');
        $carrito = $this->cliente()->carrito()->create();
        $carrito->items()->create(['producto_id' => $producto->id, 'cantidad' => 2]);
        $carrito->items()->create(['producto_id' => $otro->id, 'cantidad' => 1]);
        $this->withSession(['carrito' => [$producto->id => 2, $otro->id => 2, $sinStock->id => 1, 999999 => 1]])
            ->post(route('login.ingresar'), ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'cliente1234'])
            ->assertRedirect(route('inicio'))->assertSessionMissing('carrito');
        $this->assertAuthenticatedAs($this->cliente(), 'web');
        $this->assertSame(3, $carrito->items()->where('producto_id', $producto->id)->first()->cantidad);
        $this->assertSame(3, $carrito->items()->where('producto_id', $otro->id)->first()->cantidad);
        $this->assertSame(2, $carrito->items()->count());
    }

    public function test_fusion_al_registrarse(): void
    {
        $producto = $this->producto('Spy x Family Vol. 1');
        $this->withSession(['carrito' => [$producto->id => 5]])->post(route('registro.guardar'), [
            'nombre' => 'Nueva', 'nombre_usuario' => 'nueva', 'email' => 'nueva@example.com',
            'password' => 'clave1234', 'password_confirmation' => 'clave1234',
        ])->assertRedirect(route('inicio'))->assertSessionMissing('carrito');
        $cliente = Cliente::where('nombre_usuario', 'nueva')->firstOrFail();
        $this->assertAuthenticatedAs($cliente, 'web');
        $this->assertSame(3, $cliente->carrito->items()->first()->cantidad);
    }

    public function test_contador_para_invitado(): void
    {
        $this->post('/carrito', ['producto_id' => 1, 'cantidad' => 2]);
        $this->get('/')->assertSee('data-cantidad="2"', false);
        $this->delete(route('carrito.quitar-invitado', 1));
        $this->get('/')->assertSee('data-cantidad="0"', false);
    }

    public function test_admin_no_puede_usar_carrito(): void
    {
        $this->actingAs(\App\Models\Administrador::firstOrFail(), 'admin');
        $this->post('/carrito', ['producto_id' => 1])->assertRedirect(route('inicio'))->assertSessionHasErrors('carrito');
        $this->get('/carrito')->assertRedirect(route('inicio'));
        $this->delete(route('carrito.quitar-invitado', 1))->assertRedirect(route('inicio'));
        $this->assertDatabaseCount('carritos', 0);
        $this->assertSame([], session('carrito', []));
    }

    public function test_el_carrito_empieza_vacio(): void
    {
        $this->actingAs($this->cliente())->get('/carrito')
            ->assertOk()
            ->assertSee('Tu carrito está vacío');
    }

    public function test_agregar_productos_y_ver_el_total(): void
    {
        $onePiece = $this->producto('One Piece Vol. 1'); // $ 14.500
        $watchmen = $this->producto('Watchmen');         // $ 54.900

        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => $onePiece->id, 'cantidad' => 2])->assertSessionHasNoErrors();
        $this->post('/carrito', ['producto_id' => $watchmen->id])->assertSessionHasNoErrors();

        // 2 x 14.500 + 54.900 = 83.900
        $this->get('/carrito')
            ->assertOk()
            ->assertSee('One Piece Vol. 1')
            ->assertSee('Watchmen')
            ->assertSee('$ 83.900');
    }

    public function test_agregar_dos_veces_suma_la_cantidad(): void
    {
        $producto = $this->producto('Naruto Vol. 1');

        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => $producto->id]);
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2]);

        $item = $this->cliente()->carrito->items()->first();
        $this->assertSame(3, $item->cantidad);
        $this->assertSame(1, $this->cliente()->carrito->items()->count());
    }

    public function test_no_se_puede_superar_el_stock(): void
    {
        $producto = $this->producto('Spy x Family Vol. 1'); // stock 3

        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])->assertSessionHasNoErrors();
        $this->post('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])
            ->assertSessionHasErrors(['carrito' => 'Solo quedan 3 unidades de Spy x Family Vol. 1.']);

        $this->assertSame(2, $this->cliente()->carrito->items()->first()->cantidad);
    }

    public function test_no_se_puede_agregar_un_producto_sin_stock(): void
    {
        $producto = $this->producto('Maus I: Mi padre sangra historia'); // stock 0

        $this->actingAs($this->cliente())
            ->post('/carrito', ['producto_id' => $producto->id])
            ->assertSessionHasErrors('carrito');

        $this->assertSame(0, $this->cliente()->carrito->items()->count());
    }

    public function test_cantidades_y_productos_invalidos_se_rechazan(): void
    {
        $this->actingAs($this->cliente());

        $this->post('/carrito', ['producto_id' => 9999])->assertSessionHasErrors('producto_id');
        $this->post('/carrito', ['producto_id' => 1, 'cantidad' => 0])->assertSessionHasErrors('cantidad');
        $this->post('/carrito', ['producto_id' => 1, 'cantidad' => -5])->assertSessionHasErrors('cantidad');
        $this->post('/carrito', ['producto_id' => 1, 'cantidad' => 'muchos'])->assertSessionHasErrors('cantidad');
    }

    public function test_el_precio_sale_de_la_base_y_no_del_formulario(): void
    {
        $producto = $this->producto('One Piece Vol. 1'); // $ 14.500

        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => $producto->id, 'precio' => 1]);

        $this->assertSame(14500.0, $this->cliente()->carrito->load('items.producto')->total());
    }

    public function test_quitar_un_producto_del_carrito(): void
    {
        $producto = $this->producto('Naruto Vol. 1');
        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => $producto->id]);
        $item = $this->cliente()->carrito->items()->first();

        $this->delete(route('carrito.quitar', $item))->assertRedirect(route('carrito.ver'));

        $this->assertSame(0, $this->cliente()->carrito->items()->count());
    }

    public function test_no_se_puede_quitar_un_producto_del_carrito_de_otro_cliente(): void
    {
        $otro = Cliente::create([
            'nombre_usuario' => 'otro', 'password' => 'otro1234', 'nombre' => 'Otro', 'apellido' => 'Cliente',
            'dni' => '30111222', 'email' => 'otro@example.com',
        ]);
        $carritoAjeno = Carrito::create(['cliente_id' => $otro->id]);
        $itemAjeno = $carritoAjeno->items()->create(['producto_id' => 1, 'cantidad' => 1]);

        $this->actingAs($this->cliente())
            ->delete(route('carrito.quitar', $itemAjeno))
            ->assertNotFound();

        $this->assertModelExists($itemAjeno);
    }

    public function test_el_contador_del_header_muestra_la_cantidad(): void
    {
        $this->actingAs($this->cliente());
        $this->post('/carrito', ['producto_id' => 1, 'cantidad' => 2]);

        $this->get('/')->assertSee('data-cantidad="2"', false);
    }
}
