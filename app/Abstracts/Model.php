<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
}
