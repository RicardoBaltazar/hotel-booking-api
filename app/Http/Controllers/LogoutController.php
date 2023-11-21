<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Services\LoginService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke()
    {
        try {
            $response = $this->loginService->logout();
            return response()->json($response);

        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);

        } catch (CustomException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
