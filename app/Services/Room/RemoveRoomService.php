<?php

namespace App\Services\Room;

use App\Models\Room;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use Exception;
use Illuminate\Support\Facades\Log;

class RemoveRoomService
{
    private $authenticatedUserHandlerService;
    private $userPermissionCheckerService;
    private $modelValidatorService;
    private $roomValidatorService;
    private $room;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        UserPermissionCheckerService $userPermissionCheckerService,
        ModelValidatorService $modelValidatorService,
        RoomValidatorService $roomValidatorService,
        Room $room
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->modelValidatorService = $modelValidatorService;
        $this->roomValidatorService = $roomValidatorService;
        $this->room = $room;
    }

    public function removeRoom(int $id): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $room = $this->room->find($id);
        $this->modelValidatorService->validateIfModelHasRecords($room, 'Hotel not found');

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
