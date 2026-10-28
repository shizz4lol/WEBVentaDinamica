<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';

    protected $fillable = [
        'producto_id',
        'compra_id',
        'cantidad',
        'precio_unitario',
        'total'
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}
