<?php

namespace App\Services\Reservation;

use App\Models\Reservation;
use App\Models\Room;
use App\Services\Utils\ModelValidatorService;
use Exception;
use Illuminate\Support\Facades\Log;

class ReservationCheckoutService
{
    const AVAILABLE_STATUS_CODE = 1;

    private $modelValidatorService;
    private $reservation;
    private $room;

    public function __construct(
        ModelValidatorService $modelValidatorService,
        Reservation $reservation,
        Room $room,
    )
    {
        $this->modelValidatorService = $modelValidatorService;
        $this->reservation = $reservation;
        $this->room = $room;
    }

    public function checkout(array $data): string
    {
        // In a real application, it would be interesting not to delete the reservation
        // from the table, to serve as reports and future analyses. It would also be
        // important to create a table to store checkouts and mark them as completed.

        try {
            $reservation = $this->reservation->find($data['reservation_id']);
            $this->modelValidatorService->validateIfModelHasRecords($reservation, 'Reservation not found');

            $room = $this->room->find($reservation['room_id']);
            $this->modelValidatorService->validateIfModelHasRecords($room, 'Room not found');

            $this->markRoomAsAvaliable($room);

            $reservation->delete($reservation);

            return 'checkout completed successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $reservation;
    }

    private function markRoomAsAvaliable(object $room): void
    {
        $room->fill([
            "status_id"=> self::AVAILABLE_STATUS_CODE,
        ]);
        $room->save();
    }
}
