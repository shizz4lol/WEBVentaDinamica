<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Serie extends Model
{
    protected $table = 'series';
    public function productos():HasMany
    {
        return $this->hasMany(Producto::class, 'id_serie');
    }
    public function administrador(){
        return $this->belongsTo(Administrador::class, 'id_administrador');
    }
}
