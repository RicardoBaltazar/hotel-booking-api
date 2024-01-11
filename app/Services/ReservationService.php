<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomStatus;
use DateTime;
use Exception;
USE Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReservationService
{
    const AVAILABLE_STATUS = 'Available';

    private $authenticatedUserHandlerService;
    private $reservation;
    private $roomStatus;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        Reservation $reservation,
        Room $room,
        RoomStatus $roomStatus
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->reservation = $reservation;
        $this->roomStatus = $roomStatus;
        $this->room = $room;
    }

    public function reserveRoom(array $data): string
    {
        $currentDate = new DateTime();
        $dateProvided = new DateTime($data['reservation_date']);

        if ($dateProvided < $currentDate) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'The date provided is in the past, we cannot schedule dates that have passed.');
        }

        $room = $this->room->find($data['room_id']);
        $roomStatus = $this->roomStatus->find($room['status_id']);

        if($roomStatus['status'] != self::AVAILABLE_STATUS)
        {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'The hotel room is not available.');
        }

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $data['user_id'] = $user->id;

        $data['price'] = $room['price'] * $data['daily_rates'];
        $data['hotel_id'] = $room['hotel_id'];

        try {
            $this->reservation->create($data);
            return 'successfully booked hotel room';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
