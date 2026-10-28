<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class Cliente extends Authenticatable
{
    use Notifiable;

    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function compras(){
        return $this->hasMany(Compra::class);
    }
    public function carrito(){
        return $this->hasOne(Carrito::class);
    }
    public function favoritos(){
        return $this->hasMany(Favorito::class);
    }
}
