<?php

namespace Api\Usuarios\Requests;

use App\Abstracts\ApiRequest;
use Illuminate\Validation\Rule;

class UsuarioActualizarRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => [
                'filled',
                'string',
                'max:255',
            ],
            'email' => [
                'filled',
                Rule::unique('usuarios')->ignore($this->usuario),
                'email',
                'max:255',
            ],
            'password' => [
                'filled',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }
}
