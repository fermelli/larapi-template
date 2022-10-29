<?php

/** @var \Illuminate\Routing\Router $router */

use Api\Usuarios\Controllers\ApiTokenAutenticacionController;

$router->post('login', [ApiTokenAutenticacionController::class, 'login'])
    ->name('apitokenautenticacion.login');
