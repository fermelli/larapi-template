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

    private Usuario $usuario;
    private array $datosDeUsuarioActualizados;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usuario = Usuario::factory()->create();

        $password = Str::random(8);

        $usuarioAtributos = Usuario::factory()->raw(['password' => $password]);

        $this->datosDeUsuarioActualizados = array_merge($usuarioAtributos, ['password_confirmation' => $password]);
    }

    public function test_como_usuario_autenticado_puedo_actualizar_un_usuario()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            $this->datosDeUsuarioActualizados
        );

        $jsonData = [
            'id' => $usuarioId,
            'name' => $this->datosDeUsuarioActualizados['name'],
            'email' => $this->datosDeUsuarioActualizados['email'],
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
                'success' => false,
                'status' => 401,
                'message' => trans('auth.unauthenticated'),
            ]);
    }

    public function test_no_se_encuentra_usuario_para_actualizar()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $usuarioId = 1000;

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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['name' => ''])
        );

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

        $datosDeUsuarioActualizados = Usuario::factory()->raw();

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['name' => 12345])
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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['name' => Str::random($longitudMaxima + 1)])
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

    public function test_atributo_email_si_esta_presente_no_esta_vacio()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['email' => ''])
        );

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

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['email' => 'repetido.con.otro.usuario@email.com'])
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

        $usuario = Usuario::factory()->create(['email' => 'email@email.com']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['email' => 'email_invalido'])
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

        $usuario = Usuario::factory()->create(['email' => 'email@email.com']);

        $this->assertDatabaseHas('usuarios', $usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, ['email' => Str::random($longitudMaxima + 1) . '@email.com'])
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

    public function test_atributo_password_si_esta_presente_no_esta_vacio()
    {
        Sanctum::actingAs(Usuario::factory()->create());

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
                'password' => '',
                'password_confirmation' => '',
            ])
        );

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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
                'password' => 12345678,
                'password_confirmation' => 12345678,
            ])
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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
                'password' => $passwordMuyCorto,
                'password_confirmation' => $passwordMuyCorto,
            ])
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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
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

        $this->assertDatabaseHas('usuarios', $this->usuario->toArray());

        $usuarioId = $this->usuario->getRouteKey();

        $response = $this->putJson(
            route('usuarios.update', $usuarioId),
            array_merge($this->datosDeUsuarioActualizados, [
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
