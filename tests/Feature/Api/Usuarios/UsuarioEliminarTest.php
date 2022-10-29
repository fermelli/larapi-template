<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioEliminarTest extends TestCase
{
    use RefreshDatabase;

    public function test_puedo_eliminar_un_usuario()
    {
        $usuario = Usuario::factory()->create();

        $usuarioData = $usuario->toArray();

        $this->assertDatabaseHas('usuarios', $usuarioData);

        $usuarioId = $usuario->getRouteKey();

        $response = $this->deleteJson(route('usuarios.destroy', $usuarioId));

        $response->assertNoContent();

        $this->assertDatabaseMissing('usuarios', $usuarioData);
    }

    public function test_no_se_encuentra_el_usuario()
    {
        $usuarioId = 1;

        $response = $this->deleteJson(route('usuarios.destroy', $usuarioId));

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'status' => 404,
                'message' => trans('messages.not_found.users'),
            ]);
    }
}
