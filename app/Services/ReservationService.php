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
    const AVAILABLE_STATUS_CODE = 1;
    const OCCUPIED_STATUS_CODE = 2;
    const AVAILABLE_STATUS = 'Available';
    const OCCUPIED_STATUS = 'Occupied';

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

            $room->fill([
                "status_id"=> self::OCCUPIED_STATUS_CODE,
            ]);
            $room->save();

            return 'successfully booked hotel room';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function checkout(array $data): string
    {
        // In a real application, it would be interesting not to delete the reservation
        // from the table, to serve as reports and future analyses. It would also be
        // important to create a table to store checkouts and mark them as completed.

        try {
            $reservation = $this->reservation->find($data['reservation_id']);

            if (!$reservation) {
                throw new HttpException(404, 'Reservation not found');
            }

            $room = $this->room->find($reservation['room_id']);
            if (!$room) {
                throw new HttpException(404, 'Room not found');
            }

            $room->fill([
                "status_id"=> self::AVAILABLE_STATUS_CODE,
            ]);
            $room->save();

            $reservation->delete($reservation);

            return 'checkout completed successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $reservation;
    }
}
