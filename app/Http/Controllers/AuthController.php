<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = $this->loginService->login($credentials);
            return response()->json(['token' => $token, 'message' => 'Login bem-sucedido']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
    }
}
