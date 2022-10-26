<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UsuarioActualizarTest extends TestCase
{
    use RefreshDatabase;

    public function test_puedo_actualizar_un_usuario()
    {
        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw();

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $jsonData = [
            'id' => $usuarioId,
            'name' => $datosDeUsuarioActualizados['name'],
            'email' => $datosDeUsuarioActualizados['email'],
        ];

        $response->assertOk()
            ->assertJsonFragment($jsonData);

        $this->assertDatabaseHas('usuarios', $jsonData);
    }

    public function test_no_se_encuentra_usuario_para_actualizar()
    {
        $usuarioId = 1;

        $response = $this->putJson(route('usuarios.update', $usuarioId), []);

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'status' => 404,
                'message' => trans('messages.not_found.users'),
            ]);
    }


    public function test_atributo_name_esta_presente_y_no_vacio()
    {
        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['name' => '']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'name' => [
                        trans('validation.filled', ['attribute' => 'name']),
                    ],
                ]
            ]);
    }

    public function test_atributo_name_es_una_cadena()
    {
        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['name' => 12345]);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'name' => [
                        trans('validation.string', ['attribute' => 'name']),
                    ],
                ]
            ]);
    }

    public function test_atributo_name_tiene_tamano_maximo()
    {
        $longitudMaxima = 255;

        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['name' => Str::random($longitudMaxima + 1)]);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'name' => [
                        trans('validation.max.string', ['attribute' => 'name', 'max' => $longitudMaxima]),
                    ],
                ]
            ]);
    }

    public function test_atributo_email_esta_presente_y_no_vacio()
    {
        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['email' => '']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'email' => [
                        trans('validation.filled', ['attribute' => 'email']),
                    ],
                ]
            ]);
    }

    public function test_atributo_email_es_unico()
    {
        Usuario::factory()->create(['email' => 'repetido.con.otro.usuario@email.com']);

        $usuario = Usuario::factory()->create(['email' => 'email@email.com']);

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['email' => 'repetido.con.otro.usuario@email.com']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'email' => [
                        trans('validation.unique', ['attribute' => 'email']),
                    ],
                ]
            ]);
    }
}
