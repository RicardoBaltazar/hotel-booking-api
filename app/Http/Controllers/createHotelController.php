<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHotelRequest;
use App\Services\HotelService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class createHotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

/**
 * @OA\Post(
 *     path="/api/hotel",
 *     summary="Cadastra um novo hotel",
 *     tags={"Hotel"},
 *     description="Endpoint para criar um novo hotel com informações como nome, localização e comodidades. Somente usuários de nível admin podem cadastrar um hotel.",
 *     security={{ "bearerAuth":{} }},
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
 *         response="201",
 *         description="Hotel created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hotel cadastrado com sucesso")
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
 *         response="500",
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Internal server error occurred")
 *         )
 *     )
 * )
 */
    public function __invoke(CreateHotelRequest $request)
    {
        $data = $request->all();
        try {
            $response = $this->hotelService->createHotel($data);
            return response()->json($response, 201);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
