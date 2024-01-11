<?php

namespace App\Http\Controllers;

use App\Services\RoomService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateRoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

/**
 * @OA\Post(
 *     path="/api/room",
 *     summary="Register a new hotel room",
 *     tags={"Room"},
 *     description="Endpoint to register a new hotel room with information such as hotel id, room type, description and price. Only admin level users and the hotel administrator can register a hotel room.",
 *     security={{ "bearerAuth":{} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="hotel_id", type="int"),
 *                 @OA\Property(property="room_type_id", type="int"),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="price", type="float"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Hotel room registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hotel room registered successfully")
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
 *             @OA\Property(property="error", type="string", example="Only admin users are allowed to access this feature")
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
    public function __invoke(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->roomService->registerRoom($data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
