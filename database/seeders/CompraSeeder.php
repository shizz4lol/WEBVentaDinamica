<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompraSeeder extends Seeder
{
    public function run(): void
    {
        // Ventas históricas ficticias. El stock publicado es el saldo actual.
        DB::transaction(function () {
            $cliente = Cliente::where('nombre_usuario', 'cliente')->firstOrFail();
            $cantidades = [
                'Hunter x Hunter Vol. 1' => 25,
                'One-Punch Man Vol. 1' => 20,
                'Shingeki no Kyojin Vol. 1' => 15,
                "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1" => 10,
                'Berserk Vol. 1' => 5,
            ];
            $productos = Producto::whereIn('nombre', array_keys($cantidades))->get();
            $total = $productos->sum(fn ($p) => $p->precio * $cantidades[$p->nombre]);
            $compra = Compra::updateOrCreate([
                'cliente_id' => $cliente->id,
                'metodo_pago' => 'Prueba de seeder',
            ], [
                'fecha' => '2026-10-01',
                'nombre_cliente' => $cliente->nombre,
                'apellido_cliente' => $cliente->apellido,
                'dni_cliente' => $cliente->dni,
                'telefono_cliente' => $cliente->telefono,
                'email_cliente' => $cliente->email,
                'nombre_calle' => $cliente->nombre_calle,
                'numero_calle' => $cliente->numero_calle,
                'codigo_postal' => $cliente->codigo_postal,
                'subtotal' => $total, 'descuento' => 0, 'total' => $total,
                'estado_envio' => 'Entregado',
            ]);
            foreach ($productos as $producto) {
                $cantidad = $cantidades[$producto->nombre];
                $compra->detalles()->updateOrCreate(['producto_id' => $producto->id], [
                    'cantidad' => $cantidad, 'precio_unitario' => $producto->precio,
                    'total' => $producto->precio * $cantidad,
                ]);
            }
        });
    }
}
