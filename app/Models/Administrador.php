<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Extiende Authenticatable (y no Model) para poder iniciar sesión con el guard "admin".
#[Hidden(['password', 'remember_token'])]
class Administrador extends Authenticatable
{
    protected $table = 'administradores';
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
}
