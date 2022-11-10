<?php

namespace Api\Usuarios\Requests;

use App\Abstracts\ApiRequest;

class UsuarioCrearRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'unique:usuarios',
                'email',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
            'password_confirmation' => [
                'required_with:password'
            ]
        ];
    }
}
