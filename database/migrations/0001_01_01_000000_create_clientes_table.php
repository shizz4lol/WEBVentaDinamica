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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_usuario')->unique();
            $table->string('password');
            $table->string('nombre');
            // Apellido y DNI son opcionales al registrarse: se completan al hacer la primera compra.
            $table->string('apellido')->nullable();
            // DNI, teléfono y código postal como texto: no se hacen cuentas con ellos
            // y un teléfono como 2994123456 no entra en un integer.
            $table->string('dni', 10)->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('nombre_calle')->nullable();
            $table->unsignedInteger('numero_calle')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('localidad')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
