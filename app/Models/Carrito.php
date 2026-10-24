<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    public function detalleCompra()
    {
        return $this->belongsTo(DetalleCompra::class, 'id_detalleCompra');
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente');          
    }
    public function compra(){
        return $this->belongsTo(Compra::class, 'id_compra');
    }
}
