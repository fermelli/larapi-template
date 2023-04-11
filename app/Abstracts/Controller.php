<?php

namespace App\Abstracts;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use one2tek\larapi\Controllers\LaravelController;

class Controller extends LaravelController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Defaults.
     *
     * @var array
     */
    protected $defaults = [
        'page' => 1,
        'limit' => 10,
    ];
}
