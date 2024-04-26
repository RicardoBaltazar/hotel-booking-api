<?php

namespace App\Services\Room;

use App\Models\Hotels;
use App\Models\Room;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\UserHotelValidatorService;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateRoomService
{
    const DEFAULT_ROOM_STATUS = 1;

    private $authenticatedUserHandlerService;
    private $userPermissionCheckerService;
    private $userHotelValidatorService;
    private $modelValidatorService;
    private $hotels;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        UserPermissionCheckerService $userPermissionCheckerService,
        UserHotelValidatorService $userHotelValidatorService,
        ModelValidatorService $modelValidatorService,
        Hotels $hotels,
        Room $room
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->userHotelValidatorService = $userHotelValidatorService;
        $this->modelValidatorService = $modelValidatorService;
        $this->hotels = $hotels;
        $this->room = $room;
    }

    public function registerRoom(array $data)
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $hotel =  $this->getHotel($data);

        $this->modelValidatorService->validateIfModelHasRecords($hotel, 'Hotel not found');
        $this->userHotelValidatorService->ensureUserIsHotelAdmin($user, $hotel);
        $this->setDefaultRoomValues($data, $user);

        try {
            $this->room->create($data);
            return 'Hotel room registered successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private function getHotel(array $data): object
    {
        return $this->hotels->find($data['hotel_id']);
    }

    private function setDefaultRoomValues(array &$data, $user)
    {
        $data['user_id'] = $user->id;
        $data['status_id'] = self::DEFAULT_ROOM_STATUS;
    }

}
