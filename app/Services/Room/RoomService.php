<?php

namespace App\Services\Room;

use App\Models\Hotels;
use App\Models\Room;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\HotelValidatorService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoomService
{
    const DEFAULT_ROOM_STATUS = 1;
    const HOTEL_NOT_FOUND_MESSAGE = 'Hotel not found';
    const HTTP_STATUS_NOT_FOUND = 404;

    private $authenticatedUserHandlerService;
    private $userPermissionCheckerService;
    private $modelValidatorService;
    private $hotelValidatorService;
    private $roomValidatorService;
    private $hotels;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        UserPermissionCheckerService $userPermissionCheckerService,
        ModelValidatorService $modelValidatorService,
        HotelValidatorService $hotelValidatorService,
        RoomValidatorService $roomValidatorService,
        Hotels $hotels,
        Room $room
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->modelValidatorService = $modelValidatorService;
        $this->hotelValidatorService = $hotelValidatorService;
        $this->roomValidatorService = $roomValidatorService;
        $this->hotels = $hotels;
        $this->room = $room;
    }

    public function registerRoom($data)
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $hotel =  $this->hotels->find($data['hotel_id']);

        $this->modelValidatorService->validateIfModelHasRecords($hotel, self::HOTEL_NOT_FOUND_MESSAGE,);

        $this->hotelValidatorService->validateIfIsAdminOfHotel($user, $hotel);

        $data['user_id'] = $user->id;
        $data['status_id'] = self::DEFAULT_ROOM_STATUS;

        try {
            $this->room->create($data);
            return 'Hotel room registered successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    // public function validateIfIsAdminOfHotel(object $user, object $hotel): void
    // {
    //     if($user->id != $hotel->user_id)
    //     {
    //         throw new HttpException(403, 'Only the hotel administrator user can register a new room');
    //     }
    // }

    public function editRoom(int $id, array $data): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $room = $this->room->find($id);
        $this->modelValidatorService->validateIfModelHasRecords($room, self::HOTEL_NOT_FOUND_MESSAGE);

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $this->roomValidatorService->validateUserIsAdminOfHotelRoom($user, $room);

        try {
            $room->fill($data);
            $room->save();
            return 'Hotel room edited successfully';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function removeRoom(int $id): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $room = $this->room->find($id);
        $this->modelValidatorService->validateIfModelHasRecords($room, self::HOTEL_NOT_FOUND_MESSAGE);

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $this->roomValidatorService->validateUserIsAdminOfHotelRoom($user, $room);

        try {
            $room->delete($room);
            return 'Hotel successfully removed';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
