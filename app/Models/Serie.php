<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    protected $table = 'series';
    protected $fillable = [
        'nombre',
        'estado',
        'cant_tomos',
        'demografia'
    ];
    public function productos():HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
