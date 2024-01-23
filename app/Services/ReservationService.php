<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Services\Reservation\ReservationCalculator;
use App\Services\Utils\DateValidator;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    const AVAILABLE_STATUS_CODE = 1;
    const OCCUPIED_STATUS_CODE = 2;
    const RESERVATION_NOT_FOUND_MESSAGE = 'Reservation not found';
    const ROOM_NOT_FOUND_MESSAGE = 'Room not found';

    private $authenticatedUserHandlerService;
    private $modelValidatorService;
    private $reservationCalculator;
    private $roomValidatorService;
    private $dateValidator;
    private $reservation;
    private $roomStatus;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        ModelValidatorService $modelValidatorService,
        ReservationCalculator $reservationCalculator,
        RoomValidatorService $roomValidatorService,
        DateValidator $dateValidator,
        Reservation $reservation,
        Room $room,
        RoomStatus $roomStatus
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->modelValidatorService = $modelValidatorService;
        $this->reservationCalculator = $reservationCalculator;
        $this->roomValidatorService = $roomValidatorService;
        $this->dateValidator = $dateValidator;
        $this->reservation = $reservation;
        $this->roomStatus = $roomStatus;
        $this->room = $room;
    }

    public function reserveRoom(array $data): string
    {
        $dateProvided = new DateTime($data['reservation_date']);

        $this->dateValidator->validateDate($dateProvided);

        $room = $this->room->find($data['room_id']);
        $roomStatus = $this->roomStatus->find($room->status_id);

        $this->roomValidatorService->validateRoomStatus($roomStatus);

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $data['user_id'] = $user->id;

        $data['price'] = $this->reservationCalculator->calculateTotalCost($room['price'], $data['daily_rates']);
        $data['hotel_id'] = $room['hotel_id'];

        try {
            $this->reservation->create($data);

            $room->fill([
                "status_id" => self::OCCUPIED_STATUS_CODE,
            ]);
            $room->save();

            Log::info('successfully booked hotel room');
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
            $this->modelValidatorService->validateIfModelHasRecords($reservation, self::RESERVATION_NOT_FOUND_MESSAGE);

            $room = $this->room->find($reservation['room_id']);
            $this->modelValidatorService->validateIfModelHasRecords($room, self::ROOM_NOT_FOUND_MESSAGE);

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
