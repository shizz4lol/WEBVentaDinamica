<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            // created_at reemplaza a la antigua columna "fecha"
            $table->timestamps();
            // un cliente no puede marcar dos veces el mismo producto
            $table->unique(['cliente_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};
