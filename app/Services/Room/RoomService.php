<?php

namespace App\Services\Room;

use App\Models\Hotels;
use App\Models\Room;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidationService;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoomService
{
    const DEFAULT_ROOM_STATUS = 1;

    private $authenticatedUserHandlerService;
    private $userPermissionCheckerService;
    private $modelValidationService;
    private $hotels;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        UserPermissionCheckerService $userPermissionCheckerService,
        ModelValidationService $modelValidationService,
        Hotels $hotels,
        Room $room
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->modelValidationService = $modelValidationService;
        $this->hotels = $hotels;
        $this->room = $room;
    }

    public function registerRoom($data)
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $hotel =  $this->hotels->find($data['hotel_id']);

        $this->modelValidationService->validateIfModelHasRecords($hotel, 'Hotel not found', 404);
        $this->validateIfIsAdminOfHotel($user, $hotel);

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

    public function validateIfIsAdminOfHotel(object $user, object $hotel): void
    {
        if($user->id != $hotel->user_id)
        {
            throw new HttpException(403, 'Only the hotel administrator user can register a new room.');
        }
    }

    public function editRoom($id, $data)
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $room = $this->room->find($id);
        $this->modelValidationService->validateIfModelHasRecords($room, 'Room not found', 404);

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();

        if($user->id != $room->user_id)
        {
            throw new HttpException(403, 'Only the hotel administrator user can edit a hotel room.');
        }

        try {
            $room->fill($data);
            $room->save();
            return 'Hotel room edited successfully';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
