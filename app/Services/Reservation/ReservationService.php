<?php

namespace App\Services\Reservation;

use App\Jobs\SendEmailJob;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Reservation\ReservationCalculator;
use App\Services\Utils\DateValidator;
use App\Services\Utils\RoomValidatorService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    const OCCUPIED_STATUS_CODE = 2;
    const ROOM_NOT_FOUND_MESSAGE = 'Room not found';
    const RESERVATION_SUCCESS_MESSAGE = 'successfully booked hotel room';

    private $authenticatedUserHandlerService;
    private $reservationCalculator;
    private $roomValidatorService;
    private $dateValidator;
    private $reservation;
    private $roomStatus;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        ReservationCalculator $reservationCalculator,
        RoomValidatorService $roomValidatorService,
        DateValidator $dateValidator,
        Reservation $reservation,
        Room $room,
        RoomStatus $roomStatus
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->reservationCalculator = $reservationCalculator;
        $this->roomValidatorService = $roomValidatorService;
        $this->dateValidator = $dateValidator;
        $this->reservation = $reservation;
        $this->roomStatus = $roomStatus;
        $this->room = $room;
    }

    public function reserveRoom(array $data)
    {
        $dateProvided = $this->createDateFromReservationDate($data);

        $this->dateValidator->validateDate($dateProvided);

        $room = $this->room->find($data['room_id']);
        $roomStatus = $this->roomStatus->find($room->status_id);

        $this->roomValidatorService->validateRoomStatus($roomStatus);

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();

        // $data = $this->prepareReservationData($data, $user, $room);
        $this->prepareReservationData($data, $user, $room);

        try {
            $this->reservation->create($data);

            $this->markRoomAsOccupied($room);
            $this->sendReservationEmail($user);
            $this->logReservationSuccess();

            return self::RESERVATION_SUCCESS_MESSAGE;

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private function createDateFromReservationDate(array $data): DateTime
    {
        return new DateTime($data['reservation_date']);
    }

    private function prepareReservationData(array &$data, object $user, object $room): void
    {
        $data['user_id'] = $user->id;
        $data['price'] = $this->reservationCalculator->calculateTotalCost($room->price, $data['daily_rates']);
        // $data['hotel_id'] = $room['hotel_id'];
        $data['hotel_id'] = $room->hotel_id;
        // return $data;
    }

    private function markRoomAsOccupied(object $room): void
    {
        $room->fill(["status_id" => self::OCCUPIED_STATUS_CODE]);
        $room->save();
    }

    private function sendReservationEmail(object $user): void
    {
        SendEmailJob::dispatch(
            $user->email,
            'Email Subject',
            self::RESERVATION_SUCCESS_MESSAGE
        );
    }

    private function logReservationSuccess(): void
    {
        Log::info(self::RESERVATION_SUCCESS_MESSAGE);
    }
}
