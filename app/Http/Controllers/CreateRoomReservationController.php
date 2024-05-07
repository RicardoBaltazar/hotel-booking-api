<?php

namespace App\Http\Controllers;

use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateRoomReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }


    /**
 * @OA\Post(
 *     path="/api/rooms/reservations",
 *     summary="book a hotel room",
 *     tags={"Reservation"},
 *     description="Make a reservation for a hotel room.",
 *     security={{ "bearerAuth":{} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="room_id", type="int"),
 *                 @OA\Property(property="daily_rates", type="int"),
 *                 @OA\Property(property="reservation_date", type="string"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="successfully booked hotel room",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="successfully booked hotel room")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="The date provided is in the past, we cannot schedule dates that have passed.")
 *         )
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="The hotel room is not available.")
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
            $response = $this->reservationService->reserveRoom($data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
