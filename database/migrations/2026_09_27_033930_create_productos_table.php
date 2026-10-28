<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo', 10)->index(); // 'manga' o 'comic'
            $table->integer('orden')->nullable();
            $table->decimal('precio', 10, 2);
            $table->text('descripcion');
            $table->unsignedInteger('numero')->nullable(); // número de tomo
            $table->unsignedInteger('numero_paginas')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('imagen')->nullable(); // ruta dentro de public/, ej: img/portadas/naruto-1.jpg
            // Si el producto no pertenece a una serie (tomo único), queda en null.
            $table->foreignId('serie_id')
                ->nullable()
                ->constrained('series')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
