<?php

namespace Api\Usuarios\Requests;

use App\Abstracts\ApiRequest;

class ApiTokenLoginRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
