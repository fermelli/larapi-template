<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UsuarioGuardarTest extends TestCase
{
    use RefreshDatabase;

    public function test_puedo_guardar_un_usuario()
    {
        $usuario = Usuario::factory()->raw();

        $this->assertDatabaseMissing('usuarios', $usuario);

        $response = $this->postJson(route('usuarios.store'), $usuario);

        $jsonData = [
            'name' => $usuario['name'],
            'email' => $usuario['email'],
        ];

        $response->assertCreated()
            ->assertJsonFragment($jsonData);

        $this->assertDatabaseHas('usuarios', $jsonData);
    }

    public function test_atributo_name_es_requerido()
    {
        $usuario = Usuario::factory()->raw(['name' => '']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'name' => [
                        trans('validation.required', ['attribute' => 'name']),
                    ],
                ]
            ]);
    }

    public function test_atributo_name_es_una_cadena()
    {
        $usuario = Usuario::factory()->raw(['name' => 12345]);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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

        $usuario = Usuario::factory()->raw(['name' => Str::random($longitudMaxima + 1)]);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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

    public function test_atributo_email_es_requerido()
    {
        $usuario = Usuario::factory()->raw(['email' => '']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute' => 'email']),
                    ],
                ]
            ]);
    }

    public function test_atributo_email_es_unico()
    {
        Usuario::factory()->create(['email' => 'repetido@email.com']);

        $usuario = Usuario::factory()->raw(['email' => 'repetido@email.com']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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
        $usuario = Usuario::factory()->raw(['email' => 'email_invalido']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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
        $longitudMaxima = 255;

        $usuario = Usuario::factory()->raw(['email' => Str::random($longitudMaxima + 1) . '@email.com']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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

    public function test_atributo_password_es_requerido()
    {
        $usuario = Usuario::factory()->raw(['password' => '']);

        $response = $this->postJson(route('usuarios.store'), $usuario);

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.required', ['attribute' => 'password']),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_es_una_cadena()
    {
        $usuario = Usuario::factory()->raw(['password' => 12345678]);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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
        $longitudMinima = 8;

        $usuario = Usuario::factory()->raw(['password' => Str::random($longitudMinima - 1)]);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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
        $longitudMaxima = 255;

        $usuario = Usuario::factory()->raw(['password' => Str::random($longitudMaxima + 1)]);

        $response = $this->postJson(route('usuarios.store'), $usuario);

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
