<?php

namespace Tests\Feature;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioLeerTest extends TestCase
{
    use RefreshDatabase;

    public function test_puedo_obtener_un_usuario()
    {
        $usuario = Usuario::factory()->create();

        $response = $this->getJson(route('usuarios.show', $usuario->getRouteKey()));

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

    public function test_no_se_encuentra_usuario()
    {
        $usuarioId = 1;

        $response = $this->getJson(route('usuarios.show', $usuarioId));

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'status' => 404,
                'message' => trans('messages.not_found.users'),
            ]);
    }

    public function test_puedo_obtener_todos_los_usuarios()
    {
        $usuarios = Usuario::factory()->count(3)->create();

        $response = $this->getJson(route('usuarios.index'));

        $response->assertOk()
            ->assertExactJson([
                'total_data' => count($usuarios),
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
                ],
            ]);
    }
}
