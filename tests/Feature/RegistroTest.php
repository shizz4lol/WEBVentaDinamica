<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'nombre' => 'Ámbar',
            'nombre_usuario' => 'ambar',
            'email' => 'ambar@example.com',
            'password' => 'mangas1234',
            'password_confirmation' => 'mangas1234',
        ], $cambios);
    }

    public function test_la_pagina_de_registro_se_ve_y_tiene_link_desde_el_login(): void
    {
        $this->get('/registro')->assertOk()->assertSee('CREAR CUENTA')->assertSee('name="_token"', false);
        $this->get('/login')->assertSee(route('registro'));
        $this->get('/')->assertSee('Crear cuenta');
    }

    public function test_registro_valido_crea_la_cuenta_y_entra_solo(): void
    {
        $this->post('/registro', $this->datosValidos())->assertRedirect(route('inicio'));

        $cliente = Cliente::where('email', 'ambar@example.com')->first();
        $this->assertNotNull($cliente);
        $this->assertNotSame('mangas1234', $cliente->password);
        $this->assertTrue(Hash::check('mangas1234', $cliente->password));
        $this->assertAuthenticatedAs($cliente, 'web');
    }

    public function test_no_se_puede_repetir_el_email(): void
    {
        // cliente@example.com ya existe (ClienteSeeder)
        $this->post('/registro', $this->datosValidos(['email' => 'cliente@example.com']))
            ->assertSessionHasErrors(['email' => 'Ya hay una cuenta con ese email.']);

        $this->assertGuest('web');
    }

    public function test_las_contrasenas_tienen_que_coincidir(): void
    {
        $this->post('/registro', $this->datosValidos(['password_confirmation' => 'otra1234']))
            ->assertSessionHasErrors(['password' => 'Las contraseñas no coinciden.']);

        $this->assertDatabaseMissing('clientes', ['email' => 'ambar@example.com']);
    }

    public function test_no_se_puede_repetir_el_usuario_ni_usar_contrasenas_cortas(): void
    {
        $this->post('/registro', $this->datosValidos(['nombre_usuario' => 'cliente']))->assertSessionHasErrors('nombre_usuario');
        $this->post('/registro', $this->datosValidos(['password' => 'corta', 'password_confirmation' => 'corta']))->assertSessionHasErrors('password');
    }

    public function test_despues_de_registrarse_puede_iniciar_sesion(): void
    {
        $this->post('/registro', $this->datosValidos());
        $this->post('/logout');

        $this->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'ambar', 'password' => 'mangas1234'])
            ->assertRedirect(route('inicio'));
    }
}
