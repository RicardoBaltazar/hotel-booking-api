<?php

namespace App\Services\Hotel;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditHotelService
{
    use AuthenticatedUserIdTrait;

    private $userPermissionCheckerService;
    private $role;
    private $hotels;

    public function __construct(
        UserPermissionCheckerService $userPermissionCheckerService,
        Role $role,
        Hotels $hotels
    )
    {
        $this->userPermissionCheckerService = $userPermissionCheckerService;
        $this->role = $role;
        $this->hotels = $hotels;
    }

    public function editHotel(int $id, array $data): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();
        $hotel = $this->hotels->find($id);

        $this->ensureHotelExists($hotel);

        try {
            $hotel->fill($data);
            $hotel->save();
            return 'Hotel edited successfully';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function ensureHotelExists(object $data): void
    {
        if (!$data) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Hotel not found');
        }
    }
}
