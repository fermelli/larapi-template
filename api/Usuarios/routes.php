<?php

/** @var \Illuminate\Routing\Router $router */

use Api\Usuarios\Controllers\UsuarioController;

$router->apiResource('usuarios', UsuarioController::class);
