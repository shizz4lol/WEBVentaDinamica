<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SobreNosotrosTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_sobre_nosotros_responde(): void
    {
        $this->get('/sobre-nosotros')
            ->assertOk()
            ->assertSee('SOBRE NOSOTROS')
            ->assertSee('tienda online de cómics y mangas');

        $this->get('/')->assertSee(route('sobre-nosotros'));
    }
}
