<?php

namespace App\ExceptionsFormatters;

class UnprocessableEntityHttpExceptionFormatter
{
    private const STATUS_CODE = 422;
    private const MESSAGE_KEY = 'messages.validation_failed';

    public function format($request, $e)
    {
        $data = [
            'success' => false,
            'status' => self::STATUS_CODE,
            'message' => trans(self::MESSAGE_KEY),
        ];

        $decoded = json_decode($e->getMessage(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $decoded = [[$e->getMessage()]];
        }

        $data['errors'] = $decoded;

        return response()->json($data, self::STATUS_CODE);
    }
}
