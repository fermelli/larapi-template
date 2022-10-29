<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsuarioActualizarTest extends TestCase
{
    use RefreshDatabase;

    public function test_como_usuario_autenticado_puedo_actualizar_un_usuario()
    {
        Sanctum::actingAs(Usuario::factory()->create());

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

    public function test_como_usuario_no_autenticado_no_puedo_actualizar_un_usuario()
    {
        $usuarioId = 1;

        $response = $this->putJson(route('usuarios.update', $usuarioId), []);

        $response->assertUnauthorized()
            ->assertExactJson([
                "success" => false,
                "status" => 401,
                "message" => trans('auth.unauthenticated'),
            ]);
    }

    public function test_no_se_encuentra_usuario_para_actualizar()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuarioId = 1;

        $response = $this->putJson(route('usuarios.update', $usuarioId), []);

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'status' => 404,
                'message' => trans('messages.not_found.users'),
            ]);
    }

    public function test_atributo_name_si_esta_presente_no_esta_vacio()
    {
        Sanctum::actingAs(Usuario::factory()->create());

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
        Sanctum::actingAs(Usuario::factory()->create());

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
        Sanctum::actingAs(Usuario::factory()->create());

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

    public function test_atributo_email_si_esta_presente_no_esta_vacio()
    {
        Sanctum::actingAs(Usuario::factory()->create());

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
        Sanctum::actingAs(Usuario::factory()->create());

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

    public function test_atributo_email_es_un_email_valido()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuario = Usuario::factory()->create(['email' => 'email@email.com']);

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['email' => 'email_invalido']);

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
                        trans('validation.email', ['attribute' => 'email']),
                    ],
                ]
            ]);
    }

    public function test_atributo_email_tiene_tamano_maximo()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $longitudMaxima = 255;

        $usuario = Usuario::factory()->create(['email' => 'email@email.com']);

        $datosDeUsuarioActualizados = Usuario::factory()->raw(
            ['email' => Str::random($longitudMaxima + 1) . '@email.com'],
        );

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
                        trans('validation.max.string', ['attribute' => 'email', 'max' => $longitudMaxima]),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_si_esta_presente_no_esta_vacio()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['password' => '']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.filled', ['attribute' => 'password']),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_es_una_cadena()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['password' => 12345678]);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.string', ['attribute' => 'password']),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_tiene_tamano_minimo()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $longitudMinima = 8;

        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['password' => Str::random($longitudMinima - 1)]);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.min.string', ['attribute' => 'password', 'min' => $longitudMinima]),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_tiene_tamano_maximo()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $longitudMaxima = 255;

        $usuario = Usuario::factory()->create();

        $datosDeUsuarioActualizados = Usuario::factory()->raw(['password' => Str::random($longitudMaxima + 1)]);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $usuario->getRouteKey();

        $response = $this->putJson(route('usuarios.update', $usuarioId), $datosDeUsuarioActualizados);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.max.string', ['attribute' => 'password', 'max' => $longitudMaxima]),
                    ],
                ]
            ]);
    }
}
