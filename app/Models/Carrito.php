<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Carrito extends Model
{
    protected $table = 'carritos';
    protected $fillable = ['cliente_id'];

    // Reutilizamos la vista sin guardar el carrito invitado en la base.
    public static function deSesion(Session $sesion): self
    {
        $cantidades = $sesion->get('carrito', []);
        $items = Producto::whereIn('id', array_keys($cantidades))->get()->map(function ($producto) use ($cantidades) {
            $item = new CarritoItem(['producto_id' => $producto->id, 'cantidad' => $cantidades[$producto->id]]);

            return $item->setRelation('producto', $producto);
        });

        return (new self)->setRelation('items', $items);
    }

    public static function fusionarSesion(Cliente $cliente, Session $sesion): void
    {
        $cantidades = $sesion->get('carrito', []);
        if (! $cantidades) {
            $sesion->forget('carrito');

            return;
        }

        DB::transaction(function () use ($cliente, $cantidades) {
            $productos = Producto::whereIn('id', array_keys($cantidades))->orderBy('id')->lockForUpdate()->get();
            $carrito = $cliente->carrito()->firstOrCreate();
            foreach ($productos as $producto) {
                $item = $carrito->items()->firstOrNew(['producto_id' => $producto->id]);
                $cantidad = min($producto->stock, ($item->cantidad ?? 0) + $cantidades[$producto->id]);
                if ($cantidad > 0) {
                    $item->cantidad = $cantidad;
                    $item->save();
                } elseif ($item->exists) {
                    $item->delete();
                }
            }
        });

        $sesion->forget('carrito');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function items(){
        return $this->hasMany(CarritoItem::class);
    }

    // El total se calcula siempre con el precio actual de la base,
    // nunca con un precio que venga del formulario.
    public function total(): float
    {
        return $this->items->sum(fn (CarritoItem $item) => $item->cantidad * (float) $item->producto->precio);
    }
    public function cantidadDeProductos(): int
    {
        return (int) $this->items->sum('cantidad');
    }
}
