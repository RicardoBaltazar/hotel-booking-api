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

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="getProjectsList",
     *      tags={"Login"},
     *      summary="Make user login",
     *      description="Returns auth token",
     *      @OA\Response(
     *          response=200,
     *          description="successful login"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     * Returns list of projects
     */
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
