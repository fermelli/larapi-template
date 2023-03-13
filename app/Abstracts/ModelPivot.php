<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Relations\Pivot as BaseModelPivot;

class ModelPivot extends BaseModelPivot
{
    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
}
