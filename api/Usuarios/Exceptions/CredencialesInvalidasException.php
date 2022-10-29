<?php

namespace Api\Usuarios\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CredencialesInvalidasException extends UnauthorizedHttpException
{
    public function __construct(\Exception $previous = null, $code = 0)
    {
        $message = trans('auth.failed');

        parent::__construct('', $message, $previous, $code);
    }
}
