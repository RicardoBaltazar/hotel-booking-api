<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHotelRequest;
use App\Services\Hotel\EditHotelService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditHotelController extends Controller
{
    protected $editHotelService;

    public function __construct(EditHotelService $editHotelService)
    {
        $this->editHotelService = $editHotelService;
    }

 /**
 * @OA\Put(
 *     path="/api/hotel/{id}",
 *     summary="Edita informações de um hotel",
 *     tags={"Hotel"},
 *     description="Endpoint para editar informações de um hotel existente. Somente usuários de nível admin podem editar informações de um hotel.",
 *     security={{ "bearerAuth":{} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do hotel a ser editado",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="location", type="string"),
 *                 @OA\Property(property="amenities", type="string"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Hotel edited successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hotel editado com sucesso")
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
 *         header="Content-Type",
 *         description="application/json",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="Accept",
 *         description="application/json",
 *         @OA\Schema(type="string")
 *     )
 * )
 */
    public function __invoke(int $id, CreateHotelRequest $request)
    {
        $data = $request->all();
        try {
            $response = $this->editHotelService->editHotel($id, $data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
