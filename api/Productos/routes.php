<?php

/** @var \Illuminate\Routing\Router $router */

use Api\Productos\Controllers\ProductoController;

$router->apiResource('productos', ProductoController::class);