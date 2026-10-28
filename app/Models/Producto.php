<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Producto extends Model
{
    public const TIPO_MANGA = 'manga';
    public const TIPO_COMIC = 'comic';

    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'tipo',
        'orden',
        'precio',
        'descripcion',
        'numero',
        'numero_paginas',
        'stock',
        'imagen',
        'serie_id'
    ];
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'orden' => 'integer',
        ];
    }
    // Precio listo para mostrar: "$ 14.500"
    public function precioFormateado(): string
    {
        return '$ '.number_format((float) $this->precio, 0, ',', '.');
    }
    public function serie():BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }
    public function favoritos(){
        return $this->hasMany(Favorito::class);
    }
    public function carritoItems(){
        return $this->hasMany(CarritoItem::class);
    }
}
