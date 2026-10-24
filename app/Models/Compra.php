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
        'numero_tarjeta',
        'estado_envio',
        'id_cliente',
        'id_carrito'
    ];
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
