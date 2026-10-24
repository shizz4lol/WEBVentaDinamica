<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function producto(){
        return $this->belongsTo(Producto::class, 'id_producto');
    }
    
}
