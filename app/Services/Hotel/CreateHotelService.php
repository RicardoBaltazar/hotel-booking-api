<?php

namespace App\Services\Hotel;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateHotelService
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerService;
    private $userPermissionCheckerService;
    private $role;
    private $hotels;

    public function __construct(
        AuthenticatedUserHandlerService $authenticatedUserHandlerService,
        UserPermissionCheckerService $userPermissionCheckerService,
        Role $role,
        Hotels $hotels
    )
    {
        $this->authenticatedUserHandlerService = $authenticatedUserHandlerService;
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->role = $role;
        $this->hotels = $hotels;
    }

    public function createHotel(array $data)
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        $data['user_id'] = $user->id;
        $data['release_date'] = $this->getCurrentReleaseDate();

        try {
            $this->hotels->create($data);
            return 'Hotel registered successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function getCurrentReleaseDate(): string
    {
        return date('Y-m-d');
    }
}
