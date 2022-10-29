<?php

namespace Api\Usuarios\Services;

use Api\Usuarios\Exceptions\CredencialesInvalidasException;
use Api\Usuarios\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiTokenAutenticacionService
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function login($email, $password)
    {
        $usuario = $this->usuarioRepository->getWhere('email', $email)->first();

        if (is_null($usuario)) {
            throw new CredencialesInvalidasException;
        }

        if (!Hash::check($password, $usuario->password)) {
            throw new CredencialesInvalidasException;
        }

        $token = $usuario->createToken($email);

        return $token->plainTextToken;
    }

    public function usuario()
    {
        return Auth::user();
    }

    public function logout()
    {
        /** @var \Illuminate\Foundation\Auth\User $usuarioAutenticado */
        $usuarioAutenticado = Auth::user();

        if (!is_null($usuarioAutenticado)) {
            $usuarioAutenticado->currentAccessToken()->delete();
        }
    }
}
