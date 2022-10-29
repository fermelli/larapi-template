<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsuarioLeerTest extends TestCase
{
    use RefreshDatabase;

    public function test_como_usuario_autenticado_puedo_obtener_un_usuario()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuario = Usuario::factory()->create();

        $usuarioId = $usuario->getRouteKey();

        $response = $this->getJson(route('usuarios.show', $usuarioId));

        $response->assertOk()
            ->assertExactJson([
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'email_verified_at' => $usuario->email_verified_at,
                    'created_at' => $usuario->created_at,
                    'updated_at' => $usuario->updated_at,
                ],
            ]);
    }

    public function test_como_usuario_no_autenticado_no_puedo_obtener_un_usuario()
    {
        $usuarioId = 1;

        $response = $this->getJson(route('usuarios.show', $usuarioId));

        $response->assertUnauthorized()
            ->assertExactJson([
                'success' => false,
                'status' => 401,
                'message' => trans('auth.unauthenticated'),
            ]);
    }

    public function test_no_se_encuentra_usuario()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuarioId = 10000;

        $response = $this->getJson(route('usuarios.show', $usuarioId));

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'status' => 404,
                'message' => trans('messages.not_found.users'),
            ]);
    }

    public function test_como_usuario_autenticado_puedo_obtener_todos_los_usuarios()
    {
        $usuarioAutenticado = Usuario::factory()->create();

        Sanctum::actingAs($usuarioAutenticado);

        $usuarios = Usuario::factory()->count(3)->create();

        $response = $this->getJson(route('usuarios.index'));

        $response->assertOk()
            ->assertExactJson([
                'total_data' => count($usuarios) + 1,
                'rows' => [
                    [
                        'id' => $usuarios[2]->id,
                        'name' => $usuarios[2]->name,
                        'email' => $usuarios[2]->email,
                        'email_verified_at' => $usuarios[2]->email_verified_at,
                        'created_at' => $usuarios[2]->created_at,
                        'updated_at' => $usuarios[2]->updated_at,
                    ],
                    [
                        'id' => $usuarios[1]->id,
                        'name' => $usuarios[1]->name,
                        'email' => $usuarios[1]->email,
                        'email_verified_at' => $usuarios[1]->email_verified_at,
                        'created_at' => $usuarios[1]->created_at,
                        'updated_at' => $usuarios[1]->updated_at,
                    ],
                    [
                        'id' => $usuarios[0]->id,
                        'name' => $usuarios[0]->name,
                        'email' => $usuarios[0]->email,
                        'email_verified_at' => $usuarios[0]->email_verified_at,
                        'created_at' => $usuarios[0]->created_at,
                        'updated_at' => $usuarios[0]->updated_at,
                    ],
                    [
                        'id' => $usuarioAutenticado->id,
                        'name' => $usuarioAutenticado->name,
                        'email' => $usuarioAutenticado->email,
                        'email_verified_at' => $usuarioAutenticado->email_verified_at,
                        'created_at' => $usuarioAutenticado->created_at,
                        'updated_at' => $usuarioAutenticado->updated_at,
                    ],
                ],
            ]);
    }

    public function test_como_usuario_no_autenticado_no_puedo_obtener_todos_los_usuarios()
    {
        $response = $this->getJson(route('usuarios.index'));

        $response->assertUnauthorized()
            ->assertExactJson([
                'success' => false,
                'status' => 401,
                'message' => trans('auth.unauthenticated'),
            ]);
    }
}
