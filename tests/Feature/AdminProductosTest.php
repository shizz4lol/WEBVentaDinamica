<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_invitados_no_pueden_acceder_a_operaciones_de_productos_ni_a_la_busqueda_de_series(): void
    {
        $producto = Producto::firstOrFail();

        $this->get(route('admin.productos.index'))->assertRedirect(route('login'));
        $this->get(route('admin.productos.create'))->assertRedirect(route('login'));
        $this->get(route('admin.productos.edit', $producto))->assertRedirect(route('login'));
        $this->post(route('admin.productos.store'))->assertRedirect(route('login'));
        $this->put(route('admin.productos.update', $producto))->assertRedirect(route('login'));
        $this->delete(route('admin.productos.destroy', $producto))->assertRedirect(route('login'));
        $this->get(route('admin.series.buscar'))->assertRedirect(route('login'));
    }

    public function test_clientes_no_pueden_acceder_a_operaciones_de_productos_ni_a_la_busqueda_de_series(): void
    {
        $this->actingAs(Cliente::firstOrFail(), 'web');
        $producto = Producto::firstOrFail();

        $this->get(route('admin.productos.index'))->assertRedirect(route('login'));
        $this->get(route('admin.productos.create'))->assertRedirect(route('login'));
        $this->get(route('admin.productos.edit', $producto))->assertRedirect(route('login'));
        $this->post(route('admin.productos.store'))->assertRedirect(route('login'));
        $this->put(route('admin.productos.update', $producto))->assertRedirect(route('login'));
        $this->delete(route('admin.productos.destroy', $producto))->assertRedirect(route('login'));
        $this->get(route('admin.series.buscar'))->assertRedirect(route('login'));
    }

    public function test_administrador_puede_ver_panel_y_formularios_del_catalogo(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $producto = Producto::firstOrFail();

        $this->get(route('admin.productos.index'))
            ->assertOk()
            ->assertSee('Crear producto')
            ->assertSee('data-modal-confirmacion', false);
        $this->get(route('admin.productos.create'))
            ->assertOk()
            ->assertSee('name="producto[nombre]"', false)
            ->assertSee('data-buscador-series', false)
            ->assertSee('data-selector-imagen', false)
            ->assertSee('name="_token"', false);
        $this->get(route('admin.productos.edit', $producto))
            ->assertOk()
            ->assertSee('name="_method" value="PUT"', false)
            ->assertSee($producto->nombre);
    }

    public function test_crea_un_producto_con_serie_opcional_e_imagen(): void
    {
        Storage::fake('public');
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $serie = Serie::where('nombre', 'One Piece')->firstOrFail();
        $datos = $this->datosValidos();
        $datos['producto']['serie_id'] = $serie->id;

        $this->post(route('admin.productos.store'), $datos)
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHas('estado', 'El producto se creó correctamente.');

        $producto = Producto::where('nombre', 'Producto creado para prueba')->firstOrFail();
        $this->assertSame($serie->id, $producto->serie_id);
        $this->assertSame('producto-creado-para-prueba', $producto->slug);
        $this->assertSame('manga', $producto->tipo);
        $this->assertSame(9, $producto->stock);
        $this->assertStringStartsWith('storage/productos/', $producto->imagen);
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'numero_paginas' => 180,
            'descripcion' => 'Descripción de prueba para el producto.',
        ]);
        Storage::disk('public')->assertExists(substr($producto->imagen, strlen('storage/')));
    }

    public function test_validacion_de_creacion_muestra_mensajes_en_espanol_y_conserva_los_datos(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $datos = $this->datosValidos();
        $datos['producto']['nombre'] = 'X';
        $datos['producto']['precio'] = '-1';

        $this->from(route('admin.productos.create'))
            ->post(route('admin.productos.store'), $datos)
            ->assertRedirect(route('admin.productos.create'))
            ->assertSessionHasErrors([
                'producto.nombre' => 'El nombre debe tener al menos 2 caracteres.',
                'producto.precio' => 'El precio no puede ser negativo.',
            ])
            ->assertSessionHas('_old_input.producto.nombre', 'X');

        $this->assertDatabaseMissing('productos', ['nombre' => 'X']);
    }

    public function test_rechaza_campos_que_no_pertenecen_al_modelo_del_producto(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $datos = $this->datosValidos();
        $datos['producto']['slug'] = 'campo-no-permitido';
        $datos['producto']['es_serie'] = 'false';

        $this->post(route('admin.productos.store'), $datos)
            ->assertSessionHasErrors([
                'producto' => 'Los datos enviados no tienen un formato válido.',
            ]);

        $this->assertDatabaseMissing('productos', ['nombre' => 'Producto creado para prueba']);
    }

    public function test_creacion_requiere_imagen_y_rechaza_un_archivo_que_no_es_una_imagen(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $datos = $this->datosValidos();
        unset($datos['producto']['imagen']);

        $this->post(route('admin.productos.store'), $datos)
            ->assertSessionHasErrors([
                'producto.imagen' => 'Seleccioná una imagen para el producto.',
            ]);

        $datos = $this->datosValidos();
        $datos['producto']['imagen'] = UploadedFile::fake()->create('archivo.txt', 10, 'text/plain');

        $this->post(route('admin.productos.store'), $datos)
            ->assertSessionHasErrors('producto.imagen');

        $this->assertDatabaseMissing('productos', ['nombre' => 'Producto creado para prueba']);
    }

    public function test_edita_un_producto_y_conserva_la_imagen_si_no_se_selecciona_otra(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $producto = Producto::whereDoesntHave('detalleCompras')->firstOrFail();
        $imagenAnterior = $producto->imagen;
        $datos = $this->datosValidos(false);
        $datos['producto']['nombre'] = 'Producto editado para prueba';
        $datos['producto']['tipo'] = 'comic';
        $datos['producto']['serie_id'] = '';

        $this->put(route('admin.productos.update', $producto), $datos)
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHas('estado', 'El producto se actualizó correctamente.');

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Producto editado para prueba',
            'slug' => 'producto-editado-para-prueba',
            'tipo' => 'comic',
            'serie_id' => null,
            'imagen' => $imagenAnterior,
        ]);

        $this->get('/productos/'.$producto->id)
            ->assertFound()
            ->assertRedirect(route('productos.detalle', $producto->fresh()));
    }

    public function test_nombres_repetidos_reciben_slugs_unicos(): void
    {
        Storage::fake('public');
        $this->actingAs(Administrador::firstOrFail(), 'admin');

        foreach (range(1, 2) as $numero) {
            $datos = $this->datosValidos();
            $datos['producto']['nombre'] = 'Producto con nombre repetido';
            $this->post(route('admin.productos.store'), $datos)
                ->assertRedirect(route('admin.productos.index'));
        }

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto con nombre repetido',
            'slug' => 'producto-con-nombre-repetido',
        ]);
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto con nombre repetido',
            'slug' => 'producto-con-nombre-repetido-2',
        ]);
    }

    public function test_editar_imagen_reemplaza_el_archivo_anterior_del_producto(): void
    {
        Storage::fake('public');
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $rutaImagenAnterior = 'productos/portada-anterior.jpg';
        Storage::disk('public')->put($rutaImagenAnterior, 'imagen anterior');
        $producto = Producto::create([
            'nombre' => 'Producto con imagen reemplazable',
            'slug' => 'producto-con-imagen-reemplazable',
            'tipo' => 'manga',
            'precio' => 1000,
            'descripcion' => 'Descripción para reemplazar imagen.',
            'numero' => 1,
            'numero_paginas' => 100,
            'stock' => 2,
            'imagen' => 'storage/'.$rutaImagenAnterior,
        ]);
        $datos = $this->datosValidos();
        $datos['producto']['nombre'] = $producto->nombre;

        $this->put(route('admin.productos.update', $producto), $datos)
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHas('estado', 'El producto se actualizó correctamente.');

        $producto->refresh();
        $rutaImagenNueva = substr($producto->imagen, strlen('storage/'));
        $this->assertNotSame($rutaImagenAnterior, $rutaImagenNueva);
        Storage::disk('public')->assertExists($rutaImagenNueva);
        Storage::disk('public')->assertMissing($rutaImagenAnterior);
    }

    public function test_borra_un_producto_sin_historial_y_su_imagen_administrada(): void
    {
        Storage::fake('public');
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $datos = $this->datosValidos();
        $this->post(route('admin.productos.store'), $datos);
        $producto = Producto::where('nombre', 'Producto creado para prueba')->firstOrFail();
        $rutaImagen = substr($producto->imagen, strlen('storage/'));

        $this->delete(route('admin.productos.destroy', $producto))
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHas('estado', 'El producto se borró correctamente.');

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
        Storage::disk('public')->assertMissing($rutaImagen);
    }

    public function test_rechaza_borrar_un_producto_vendido_y_conserva_su_historial(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');
        $producto = Producto::whereDoesntHave('detalleCompras')->firstOrFail();
        $compra = Compra::firstOrFail();
        $detalle = DetalleCompra::create([
            'producto_id' => $producto->id,
            'compra_id' => $compra->id,
            'cantidad' => 2,
            'precio_unitario' => $producto->precio,
            'total' => (float) $producto->precio * 2,
        ]);

        $this->from(route('admin.productos.index'))
            ->delete(route('admin.productos.destroy', $producto))
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHasErrors([
                'producto' => 'No se puede borrar este producto porque tiene compras asociadas. Se conservó el historial.',
            ]);

        $this->assertModelExists($producto);
        $this->assertModelExists($detalle);
    }

    public function test_busca_series_con_resultados_ordenados_y_limitados(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');

        foreach (range(1, 12) as $numero) {
            Serie::create(['nombre' => 'Serie de búsqueda '.sprintf('%02d', $numero)]);
        }

        $this->get(route('admin.series.buscar', ['q' => 'Serie de búsqueda']))
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonPath('0.nombre', 'Serie de búsqueda 01')
            ->assertJsonPath('9.nombre', 'Serie de búsqueda 10');
    }

    public function test_busqueda_de_series_no_acepta_entrada_que_no_sea_texto(): void
    {
        $this->actingAs(Administrador::firstOrFail(), 'admin');

        $this->get(route('admin.series.buscar', ['q' => ['One Piece']]))
            ->assertSessionHasErrors('q');
    }

    /**
     * @return array{producto: array<string, mixed>}
     */
    private function datosValidos(bool $conImagen = true): array
    {
        $producto = [
            'nombre' => 'Producto creado para prueba',
            'tipo' => 'manga',
            'orden' => '',
            'precio' => '12345.50',
            'descripcion' => 'Descripción de prueba para el producto.',
            'numero' => '1',
            'numero_paginas' => '180',
            'stock' => '9',
            'serie_id' => '',
        ];

        if ($conImagen) {
            $producto['imagen'] = UploadedFile::fake()->image('portada.jpg');
        }

        return ['producto' => $producto];
    }
}
