<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Auth\AuthenticationException;
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
            $response = $this->loginService->login($credentials);
            return response()->json($response);

        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);

        } catch (CustomException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
