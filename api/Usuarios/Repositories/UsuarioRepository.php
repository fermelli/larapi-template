<?php

namespace Api\Usuarios\Repositories;

use Api\Usuarios\Models\Usuario;
use App\Abstracts\Repository;

class UsuarioRepository extends Repository
{
    public function getModel()
    {
        return new Usuario();
    }

    public function create(array $data)
    {
        $usuario = $this->getModel();

        $data['password'] = bcrypt($data['password']);

        $usuario->fill($data);

        $usuario->save();

        return $usuario;
    }

    public function update(Usuario $usuario, array $data)
    {
        $usuario->fill($data);

        $usuario->save();

        return $usuario;
    }
}
