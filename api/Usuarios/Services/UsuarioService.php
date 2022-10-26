<?php

namespace Api\Usuarios\Services;

use Api\Usuarios\Exceptions\UsuarioNoEncontradoException;
use Api\Usuarios\Repositories\UsuarioRepository;

class UsuarioService
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function obtenerTodos($options = [])
    {
        return $this->usuarioRepository->getWithCount($options);
    }

    public function obtenerPorId($usuarioId, array $options = [])
    {
        $usuario = $this->obtenerUsuarioSolicitado($usuarioId, $options);

        return $usuario;
    }

    public function crear($data)
    {
        $usuario = $this->usuarioRepository->create($data);

        return $usuario;
    }

    public function actualizar($usuarioId, array $data)
    {
        $usuario = $this->obtenerUsuarioSolicitado($usuarioId);

        $usuario = $this->usuarioRepository->update($usuario, $data);

        return $usuario;
    }

    public function eliminar($usuarioId)
    {
        $usuario = $this->obtenerUsuarioSolicitado($usuarioId, ['select' => ['id']]);

        $this->usuarioRepository->delete($usuarioId);

        return $usuario;
    }

    private function obtenerUsuarioSolicitado($usuarioId, array $options = [])
    {
        $usuario = $this->usuarioRepository->getById($usuarioId, $options);

        if (is_null($usuario)) {
            throw new UsuarioNoEncontradoException;
        }

        return $usuario;
    }
}
