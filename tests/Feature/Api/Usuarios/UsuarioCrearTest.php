<?php

namespace Tests\Feature\Api\Usuarios;

use Api\Usuarios\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsuarioCrearTest extends TestCase
{
    use RefreshDatabase;

    private array $usuarioAtributos;
    private array $usuarioData;

    protected function setUp(): void
    {
        parent::setUp();

        $password = Str::random(8);

        $this->usuarioAtributos = Usuario::factory()->raw(['password' => $password]);

        $this->usuarioData = array_merge($this->usuarioAtributos, ['password_confirmation' => $password]);
    }

    public function test_como_usuario_autenticado_puedo_guardar_un_usuario()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $this->assertDatabaseMissing('usuarios', $this->usuarioAtributos);

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, []),
        );

        $jsonData = [
            'name' => $this->usuarioData['name'],
            'email' => $this->usuarioData['email'],
        ];

        $response->assertCreated()
            ->assertJsonFragment($jsonData);

        $this->assertDatabaseHas('usuarios', $jsonData);
    }

    public function test_como_usuario_no_autenticado_no_puedo_guardar_un_usuario()
    {
        $response = $this->postJson(route('usuarios.store'), []);

        $response->assertUnauthorized()
            ->assertExactJson([
                'success' => false,
                'status' => 401,
                'message' => trans('auth.unauthenticated'),
            ]);
    }

    public function test_atributo_name_es_requerido()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['name' => ''])
        );

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
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['name' => 12345])
        );

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

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['name' => Str::random($longitudMaxima + 1)])
        );

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
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['email' => ''])
        );

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
        Sanctum::actingAs(Usuario::factory()->create());

        Usuario::factory()->create(['email' => 'repetido@email.com']);

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['email' => 'repetido@email.com'])
        );

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

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['email' => 'email_invalido'])
        );

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

        $usuarioData = Usuario::factory()->raw();

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['email' => Str::random($longitudMaxima + 1) . '@email.com'])
        );

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
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['password' => '', 'password_confirmation' => ''])
        );

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
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, ['password' => 12345678, 'password_confirmation' => 12345678])
        );

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
        $passwordMuyCorto = Str::random($longitudMinima - 1);

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge(
                $this->usuarioData,
                [
                    'password' => $passwordMuyCorto,
                    'password_confirmation' => $passwordMuyCorto,
                ]
            )
        );

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
        $passwordMuyLargo = Str::random($longitudMaxima + 1);

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, [
                'password' => $passwordMuyLargo,
                'password_confirmation' => $passwordMuyLargo,
            ])
        );

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

    public function test_atributo_password_tiene_que_ser_confirmado()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, [
                'password' => 'password',
                'password_confirmation' => 'otro_password',
            ])
        );

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.confirmed', ['attribute' => 'password']),
                    ],
                ]
            ]);
    }

    public function test_atributo_password_confirmation_es_requerido_con_el_password()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $response = $this->postJson(
            route('usuarios.store'),
            array_merge($this->usuarioData, [
                'password' => 'password',
                'password_confirmation' => null,
            ])
        );

        $response->assertUnprocessable()
            ->assertExactJson([
                'success' => false,
                'status' => 422,
                'message' => trans('messages.validation_failed'),
                'errors' => [
                    'password' => [
                        trans('validation.confirmed', ['attribute' => 'password']),
                    ],
                    'password_confirmation' => [
                        trans('validation.required_with', [
                            'attribute' => 'password confirmation',
                            'values' => 'password',
                        ]),
                    ],
                ]
            ]);
    }
}
