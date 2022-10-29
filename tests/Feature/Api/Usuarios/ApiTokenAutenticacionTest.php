<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTokenAutenticacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_puedo_autenticarme()
    {
        $usuario = Usuario::factory()->create();

        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => $usuario->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function test_como_usuario_autenticado_puedo_desautenticarme()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(route('apitokenautenticacion.logout'));

        $response->assertNoContent();
    }

    public function test_como_usuario_no_autenticado_no_puedo_desautenticarme()
    {
        $response = $this->postJson(route('apitokenautenticacion.logout'));

        $response->assertUnauthorized()
            ->assertExactJson([
                "success" => false,
                "status" => 401,
                "message" => trans('auth.unauthenticated'),
            ]);
    }

    public function test_campo_email_es_requerido()
    {
        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => '',
            'password' => 'password',
        ]);

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

    public function test_campo_email_es_un_email_valido()
    {
        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => 'email_invalido',
            'password' => 'password',
        ]);

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

    public function test_campo_email_tiene_tamano_maximo()
    {
        $longitudMaxima = 255;

        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => Str::random($longitudMaxima + 1) . '@email.com',
            'password' => 'password',
        ]);

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

    public function test_campo_password_es_requerido()
    {
        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => 'miemail@email.com',
            'password' => '',
        ]);

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

    public function test_campo_password_es_una_cadena()
    {
        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => 'miemail@email.com',
            'password' => 12345678,
        ]);

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

    public function test_campo_password_tiene_tamano_maximo()
    {
        $longitudMaxima = 255;

        $response = $this->postJson(route('apitokenautenticacion.login'), [
            'email' => 'miemail@email.com',
            'password' => Str::random($longitudMaxima + 1),
        ]);

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

    public function test_como_usuario_autenticado_puedo_obtener_el_usuario_autenticado()
    {
        $usuario = Usuario::factory()->create();

        Sanctum::actingAs($usuario);

        $response = $this->getJson(route('apitokenautenticacion.usuario'));

        $response->assertOk()
            ->assertExactJson([
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'email_verified_at' => $usuario->email_verified_at,
                    'updated_at' => $usuario->updated_at,
                    'created_at' => $usuario->created_at,
                ],
            ]);
    }

    public function test_como_usuario_no_autenticado_no_puedo_obtener_el_usuario_autenticado()
    {
        $response = $this->getJson(route('apitokenautenticacion.usuario'));

        $response->assertUnauthorized()
            ->assertExactJson([
                'success' => false,
                'status' => 401,
                'message' => trans('auth.unauthenticated'),
            ]);
    }
}
