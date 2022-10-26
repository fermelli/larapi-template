<?php

namespace Api\Usuarios\Controllers;

use Api\Usuarios\Requests\UsuarioActualizarRequest;
use Api\Usuarios\Requests\UsuarioCrearRequest;
use Api\Usuarios\Services\UsuarioService;
use App\Abstracts\Controller;

class UsuarioController extends Controller
{
    private UsuarioService $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public function index()
    {
        $opciones = $this->parseResourceOptions();

        $datosDevueltos = $this->usuarioService->obtenerTodos($opciones);

        return $this->response($datosDevueltos);
    }

    public function show($usuarioId)
    {
        $opciones = $this->parseResourceOptions();

        $datosDevueltos['usuario'] = $this->usuarioService->obtenerPorId($usuarioId, $opciones);

        return $this->response($datosDevueltos);
    }

    public function store(UsuarioCrearRequest $request)
    {
        $datos = $request->validated();

        $datosDevueltos['usuario'] = $this->usuarioService->crear($datos);

        return $this->response($datosDevueltos, 201);
    }

    public function update($usuarioId, UsuarioActualizarRequest $request)
    {
        $datos = $request->validated();

        $datosDevueltos['usuario'] = $this->usuarioService->actualizar($usuarioId, $datos);

        return $this->response($datosDevueltos);
    }

    public function destroy($usuarioId)
    {
        $this->usuarioService->eliminar($usuarioId);

        return $this->response(null, 204);
    }
}
