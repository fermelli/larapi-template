<?php

namespace App\ExceptionsFormatters;

class AuthorizationExceptionFormatter
{
    private const STATUS_CODE = 401;
    private const MESSAGE_KEY = 'auth.unauthenticated';

    public function format($request, $e)
    {
        $data = [
            'success' => false,
            'status' => self::STATUS_CODE,
            'message' => trans(self::MESSAGE_KEY),
        ];

        return response()->json($data, self::STATUS_CODE);
    }
}
