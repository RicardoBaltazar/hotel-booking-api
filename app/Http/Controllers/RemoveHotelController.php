<?php

namespace App\Http\Controllers;

use App\Services\Hotel\RemoveHotelService;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RemoveHotelController extends Controller
{
    protected $removeHotelService;

    public function __construct(RemoveHotelService $removeHotelService)
    {
        $this->removeHotelService = $removeHotelService;
    }

    /**
 * @OA\Delete(
 *     path="/api/hotel/{id}",
 *     summary="Remover um hotel",
 *     tags={"Hotel"},
 *     description="Endpoint para remover um hotel existente. Somente usuários de nível admin podem remover um hotel.",
 *     security={{ "bearerAuth":{} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do hotel a ser removido",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Hotel removed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hotel removido com sucesso")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Invalid input")
 *         )
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Apenas usuários administradores têm permissão para acessar este recurso.")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Hotel não encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Internal server error occurred")
 *         )
 *     ),
 *     @OA\Header(
 *         header="Accept",
 *         description="application/json",
 *         @OA\Schema(type="string")
 *     )
 * )
 */
    public function __invoke(int $id)
    {
        try {
            $response = $this->removeHotelService->removeHotel($id);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
