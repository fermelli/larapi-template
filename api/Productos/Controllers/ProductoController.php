<?php

namespace Api\Productos\Controllers;

use Api\Productos\Requests\ProductoActualizarRequest;
use Api\Productos\Requests\ProductoCrearRequest;
use Api\Productos\Services\ProductoService;
use App\Abstracts\Controller;

class ProductoController extends Controller
{
    private ProductoService $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    public function index()
    {
        $opciones = $this->parseResourceOptions();

        $datosDevueltos = $this->productoService->obtenerTodos($opciones);

        return $this->response($datosDevueltos);
    }

    public function show($ProductoId)
    {
        $opciones = $this->parseResourceOptions();

        $datosDevueltos['producto'] = $this->productoService->obtenerPorId($ProductoId, $opciones);

        return $this->response($datosDevueltos);
    }

    public function store(ProductoCrearRequest $request)
    {
        $datos = $request->validated();

        $datosDevueltos['producto'] = $this->productoService->crear($datos);

        return $this->response($datosDevueltos, 201);
    }

    public function update($ProductoId, ProductoActualizarRequest $request)
    {
        $datos = $request->validated();

        $datosDevueltos['producto'] = $this->productoService->actualizar($ProductoId, $datos);

        return $this->response($datosDevueltos);
    }

    public function destroy($ProductoId)
    {
        $this->productoService->eliminar($ProductoId);

        return $this->response(null, 204);
    }
}
