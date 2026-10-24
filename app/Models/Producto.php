<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Producto extends Model
{
    protected $table = 'productos';
    public function serie():BelongsTo
    {
        return $this->belongsTo(Serie::class, 'id_serie');
    }
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'id_producto');
    }
    public function favorito(){
        return $this->hasMany(Favorito::class, 'id_producto');
    }
}
