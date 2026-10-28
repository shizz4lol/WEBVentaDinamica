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
            // Copia de los datos del cliente al momento de comprar
            // (si después cambia su dirección, la compra no se modifica).
            $table->string('nombre_cliente');
            $table->string('apellido_cliente');
            $table->string('dni_cliente', 10);
            $table->string('telefono_cliente', 20);
            $table->string('email_cliente');

            $table->string('nombre_calle');
            $table->unsignedInteger('numero_calle');
            $table->string('codigo_postal', 10);
            $table->date('fecha');

            $table->decimal('subtotal', 10, 2);
            $table->unsignedTinyInteger('descuento')->default(0); // porcentaje
            $table->decimal('total', 10, 2);

            // Nunca se guarda el número de tarjeta: solo el método elegido.
            $table->string('metodo_pago');
            $table->string('estado_envio')->default('Pendiente');
            // restrict: no se puede borrar un cliente que tiene compras,
            // así no se pierde el historial de ventas.
            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
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
