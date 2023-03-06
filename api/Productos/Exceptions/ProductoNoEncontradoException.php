<?php

namespace Api\Productos\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductoNoEncontradoException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct(trans('messages.not_found.producto'));
    }
}