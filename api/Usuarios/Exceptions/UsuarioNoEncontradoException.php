<?php

namespace Api\Usuarios\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsuarioNoEncontradoException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct(trans('messages.not_found.users'));
    }
}
