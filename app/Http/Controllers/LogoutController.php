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

/**
 * @OA\Get(
 *  path="/logout",
 *  operationId="logout",
 *  tags={"Autenticação"},
 *  summary="Realiza logout",
 *  description="Realiza logout e encerra a sessão do usuário",
 *  security={{"bearerAuth":{}}},
 *  @OA\Parameter(
 *      name="Accept",
 *      in="header",
 *      required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Logout bem-sucedido",
 *  ),
 *  @OA\Response(response=400, description="Bad request"),
 * )
 */
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
