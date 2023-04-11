<?php

/**
 * Variable for global router
 *
 * @var \Illuminate\Routing\Router $router
 * */

use Api\Usuarios\Controllers\ApiTokenAutenticacionController;
use Api\Usuarios\Controllers\UsuarioController;

$router->apiResource('usuarios', UsuarioController::class);

$router->get('usuario', [ApiTokenAutenticacionController::class, 'usuario'])
    ->name('apitokenautenticacion.usuario');
$router->post('logout', [ApiTokenAutenticacionController::class, 'logout'])
    ->name('apitokenautenticacion.logout');
