<?php

namespace Api\Usuarios\Controllers;

use Api\Usuarios\Requests\ApiTokenLoginRequest;
use Api\Usuarios\Services\ApiTokenAutenticacionService;
use App\Abstracts\Controller;

class ApiTokenAutenticacionController extends Controller
{
    private ApiTokenAutenticacionService $apiTokenAutenticacionService;

    public function __construct(ApiTokenAutenticacionService $apiTokenAutenticacionService)
    {
        $this->apiTokenAutenticacionService = $apiTokenAutenticacionService;

        $this->middleware('auth:sanctum')->except(['login']);
    }

    public function login(ApiTokenLoginRequest $request)
    {
        $credenciales = $request->validated();

        $email = $credenciales['email'];
        $password = $credenciales['password'];

        $datosDevueltos['token'] = $this->apiTokenAutenticacionService->login($email, $password);

        return $this->response($datosDevueltos);
    }

    public function usuario()
    {
        $datosDevueltos['usuario'] = $this->apiTokenAutenticacionService->usuario();

        return $this->response($datosDevueltos);
    }

    public function logout()
    {
        $this->apiTokenAutenticacionService->logout();

        return $this->response(null, 204);
    }
}
