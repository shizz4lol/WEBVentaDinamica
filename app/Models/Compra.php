<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable =[
        'numero_calle',
        'nombre_calle',
        'nombre_cliente',
        'apellido_cliente',
        'dni_cliente',
        'telefono_cliente',
        'email_cliente',
        'codigo_postal',
        'fecha',
        'subtotal',
        'descuento',
        'total',
        'metodo_pago',
        'estado_envio',
        'cliente_id'
    ];
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function detalles(){
        return $this->hasMany(DetalleCompra::class);
    }
}
