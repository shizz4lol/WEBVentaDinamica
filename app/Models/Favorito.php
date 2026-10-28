<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    protected $fillable = [
        'cliente_id',
        'producto_id'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function producto(){
        return $this->belongsTo(Producto::class);
    }

}
