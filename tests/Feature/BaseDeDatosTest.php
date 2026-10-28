<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Favorito;
use App\Models\Producto;
use App\Models\Serie;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// Comprueba que las migraciones, los modelos y los seeders coinciden entre sí.
class BaseDeDatosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_los_seeders_cargan_usuarios_y_productos(): void
    {
        $this->assertSame(1, Administrador::count());
        $this->assertSame(1, Cliente::count());
        $this->assertSame(40, Producto::where('tipo', Producto::TIPO_MANGA)->count());
        $this->assertSame(6, Producto::where('tipo', Producto::TIPO_COMIC)->count());
        $this->assertTrue(Hash::check('admin1234', Administrador::first()->password));
    }

    public function test_cada_producto_tiene_su_portada(): void
    {
        $this->assertSame(0, Producto::whereNull('imagen')->count());
        foreach (Producto::all() as $producto) {
            $this->assertFileExists(public_path($producto->imagen), $producto->nombre);
        }
    }

    public function test_las_relaciones_producto_y_serie_funcionan(): void
    {
        $producto = Producto::where('nombre', 'One Piece Vol. 1')->first();

        $this->assertInstanceOf(Serie::class, $producto->serie);
        $this->assertSame('One Piece', $producto->serie->nombre);
        $this->assertTrue($producto->serie->productos->contains($producto));
        $this->assertNull(Producto::where('nombre', 'Watchmen')->first()->serie);
    }

    public function test_el_carrito_guarda_productos_y_cantidades(): void
    {
        $cliente = Cliente::first();
        $carrito = Carrito::create(['cliente_id' => $cliente->id]);
        $carrito->items()->create(['producto_id' => Producto::first()->id, 'cantidad' => 2]);

        $this->assertSame(2, $cliente->carrito->items->first()->cantidad);
        $this->assertSame('One Piece Vol. 1', $cliente->carrito->items->first()->producto->nombre);
    }

    public function test_se_puede_guardar_un_favorito(): void
    {
        Favorito::create(['cliente_id' => Cliente::first()->id, 'producto_id' => Producto::first()->id]);

        $this->assertSame(1, Cliente::first()->favoritos()->count());
    }

    public function test_precios_y_telefonos_grandes_entran(): void
    {
        $producto = Producto::first();
        $producto->update(['precio' => 1500000]);

        $this->assertSame('1500000.00', $producto->fresh()->precio);
        $this->assertSame('2994123456', Cliente::first()->telefono);
    }

    public function test_no_se_puede_borrar_un_cliente_con_compras(): void
    {
        $cliente = Cliente::first();
        Compra::create([
            'nombre_cliente' => $cliente->nombre,
            'apellido_cliente' => $cliente->apellido,
            'dni_cliente' => $cliente->dni,
            'telefono_cliente' => $cliente->telefono,
            'email_cliente' => $cliente->email,
            'nombre_calle' => 'Av. Argentina',
            'numero_calle' => 1200,
            'codigo_postal' => '8300',
            'fecha' => now(),
            'subtotal' => 14500,
            'total' => 14500,
            'metodo_pago' => 'Efectivo',
            'cliente_id' => $cliente->id,
        ]);

        $this->expectException(QueryException::class);
        $cliente->delete();
    }
}
