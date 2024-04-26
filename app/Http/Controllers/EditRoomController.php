<?php

namespace App\Http\Controllers;

use App\Services\Room\EditRoomService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditRoomController extends Controller
{
    protected $editRoomService;

    public function __construct(EditRoomService $editRoomService)
    {
        $this->editRoomService = $editRoomService;
    }

    /**
 * @OA\Put(
 *     path="/api/room/{id}",
 *     summary="Edit hotel room information",
 *     tags={"Room"},
 *     description="Endpoint to edit information for an existing hotel room. Only admin-level users and hotel administrators can edit hotel information",
 *     security={{ "bearerAuth":{} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Hotel room ID to be edited",
 *         @OA\Schema(type="integer")
 *     ),
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
 *         response="200",
 *         description="Hotel edited successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hotel room edited successfully")
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
 *         response="404",
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Hotel not found")
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
    public function __invoke(Request $request, int $id)
    {
        $data = $request->all();

        try {
            $response = $this->editRoomService->editRoom($id, $data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
