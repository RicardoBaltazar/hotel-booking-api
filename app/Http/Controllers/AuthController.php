<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Autenticação"},
     *     summary="Realiza login e cria token de autorização",
     *     description="Retorna o token de autorização",
     *     @OA\RequestBody(
     *        description="Dados de login",
     *        required=true,
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="email",
     *                description="Email do usuário",
     *                type="string",
     *            ),
     *            @OA\Property(
     *                property="password",
     *                description="Senha do usuário",
     *                type="string",
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                property="message",
     *                type="string",
     *             ),
     *             @OA\Property(
     *                property="token",
     *                type="string",
     *             ),
     *             @OA\Property(
     *                property="access",
     *                type="string",
     *             ),
     *         ),
     *     ),
     *    @OA\Response(
     *        response=401,
     *        description="Credenciais inválidas",
     *    ),
     *     @OA\Response(response=400, description="Bad request"),
     *     security={
     *         {"api_key_security_example": {}}
     *     }
     * )
     */
    public function __invoke(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            $response = $this->loginService->login($credentials);
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
