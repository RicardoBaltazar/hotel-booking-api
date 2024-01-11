<?php

namespace App\Services;

use App\Models\Hotels;
use App\Models\Role;
use App\Traits\AuthenticatedUserIdTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HotelService
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
        $data['release_date'] = date('Y-m-d');

        try {
            $this->hotels->create($data);
            return 'Hotel registered successfully';

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function removeHotel(int $id): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();
        $hotel = $this->hotels->find($id);

        if (!$hotel) {
            throw new HttpException(404, 'Hotel not found');
        }

        try {
            $hotel->delete($hotel);
            return 'Hotel successfully removed';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function editHotel(int $id, array $data): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();
        $hotel = $this->hotels->find($id);

        if (!$hotel) {
            throw new HttpException(404, 'Hotel not found');
        }

        try {
            $hotel->fill($data);
            $hotel->save();
            return 'Hotel edited successfully';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
