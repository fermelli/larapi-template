<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelWithSoftDeletes extends BaseModel
{
    use SoftDeletes;

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    public const DELETED_AT = 'eliminado_en';
}
