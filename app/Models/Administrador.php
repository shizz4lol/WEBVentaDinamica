<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradors';
    protected $fillable = [
        'nombre_usuario', 
        'nombre', 
        'password'
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function productos(){
        return $this->hasMany(Producto::class, 'id_administrador');
    }
    public function series(){
        return $this->hasMany(Serie::class, 'id_administrador');
    }
}
