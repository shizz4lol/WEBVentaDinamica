<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_la_pagina_de_login_se_ve(): void
    {
        $this->get('/login')->assertOk()->assertSee('INICIAR SESIÓN');
    }

    public function test_un_cliente_puede_iniciar_sesion(): void
    {
        $this->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'cliente1234'])
            ->assertRedirect(route('inicio'));

        $this->assertAuthenticatedAs(Cliente::first(), 'web');
        $this->assertGuest('admin');
    }

    public function test_un_administrador_puede_iniciar_sesion_y_ver_el_panel(): void
    {
        $this->post('/login', ['tipo' => 'admin', 'nombre_usuario' => 'admin', 'password' => 'admin1234'])
            ->assertRedirect(route('admin.panel'));

        $this->assertAuthenticatedAs(Administrador::first(), 'admin');
        $this->get('/admin')->assertOk()->assertSee('PANEL DE ADMINISTRACIÓN');
    }

    public function test_con_la_contrasena_incorrecta_no_entra(): void
    {
        $this->from('/login')
            ->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'mal'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['nombre_usuario' => 'Usuario o contraseña incorrectos.']);

        $this->assertGuest('web');
    }

    public function test_un_cliente_no_puede_entrar_como_administrador(): void
    {
        $this->post('/login', ['tipo' => 'admin', 'nombre_usuario' => 'cliente', 'password' => 'cliente1234'])
            ->assertSessionHasErrors('nombre_usuario');

        $this->assertGuest('admin');
    }

    public function test_los_campos_son_obligatorios(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['tipo', 'nombre_usuario', 'password']);
    }

    public function test_el_formulario_de_login_incluye_el_token_csrf(): void
    {
        // Laravel no verifica el CSRF durante los tests, así que comprobamos que el token esté en el formulario.
        $this->get('/login')->assertSee('name="_token"', false);
    }

    public function test_despues_de_cinco_intentos_fallidos_frena_un_minuto(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'mal']);
        }

        $this->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'cliente1234'])
            ->assertStatus(429);
    }

    public function test_usar_el_formulario_de_contacto_no_bloquea_el_login(): void
    {
        // Cada formulario tiene su propio límite (revisión de Codex, Fase 2).
        for ($i = 0; $i < 6; $i++) {
            $this->post('/contacto', []);
        }

        $this->post('/login', ['tipo' => 'cliente', 'nombre_usuario' => 'cliente', 'password' => 'cliente1234'])
            ->assertRedirect(route('inicio'));
    }

    public function test_el_panel_admin_esta_cerrado_para_clientes_e_invitados(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $this->actingAs(Cliente::first(), 'web')->get('/admin')->assertRedirect('/login');
    }

    public function test_cerrar_sesion(): void
    {
        $this->actingAs(Cliente::first(), 'web')
            ->post('/logout')
            ->assertRedirect(route('inicio'));

        $this->assertGuest('web');
    }
}
