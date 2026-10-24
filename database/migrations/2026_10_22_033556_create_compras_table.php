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
        Schema::create('compras', function (Blueprint $table) {
        $table->id();
            $table->string('nombre_cliente');
            $table->string('apellido_cliente');
            $table->integer('dni_cliente');
            $table->integer('telefono_cliente');
            $table->string('email_cliente');
            
            $table->string('nombre_calle');
            $table->integer('numero_calle');
            $table->integer('codigo_postal');
            $table->date('fecha');

            $table->decimal('subtotal', 8, 2);
            $table->integer('descuento')->default(0);
            $table->decimal('total', 8, 2);

            $table->string('metodo_pago');
            $table->integer('numero_tarjeta')->nullable();
            $table->string('estado_envio')->default('Pendiente');
            $table->timestamps();
            $table->foreignId('id_cliente')
                ->nullable()
                ->constrained('clientes','id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('id_carrito')
                ->nullable()
                ->constrained('carritos','id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
