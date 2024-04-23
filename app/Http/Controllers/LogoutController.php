<?php

namespace App\Http\Controllers;

use App\Services\Auth\LogoutService;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogoutController extends Controller
{
    protected $logoutService;

    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
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
            $response = $this->logoutService->logout();
            return response()->json($response);

        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);

        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
