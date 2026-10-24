<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class Cliente extends Authenticatable
{
    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'nombre_calle',
        'numero_calle',
        'localidad',
        'codigo_postal',
        'nombre_usuario',
        'password',
        'metodo_pago'
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
    public function compra(){
        return $this->hasMany(Compra::class, 'id_cliente');
    }
    public function carrito(){
        return $this->hasMany(Carrito::class, 'id_cliente');
    }
    public function favoritos(){
        return $this->hasMany(Favorito::class, 'id_cliente');
    }
}
