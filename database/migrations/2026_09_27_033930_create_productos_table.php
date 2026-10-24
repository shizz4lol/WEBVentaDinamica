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
            $table->decimal('precio', 8, 2);
            $table->text('descripcion');
            $table->integer('numero')->nullable();
            $table->string('numero_paginas');
            $table->boolean('es_serie')->default(true);
            $table->timestamps();
            $table->foreignId('serie')
            ->constrained('series','id')
            ->onDelete('cascade')
            ->onUpdate('cascade');;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};