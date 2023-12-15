<?php

namespace App\Services;

use App\Models\Hotels;
use App\Models\Role;
use App\Traits\AuthenticatedUserIdTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
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

        // $data['user_id'] = $this->getUserId();

        $user = $this->authenticatedUserHandlerService->getAuthenticatedUser();
        // return $user;

        $data['user_id'] = $user->id;

        $data['release_date'] = date('Y-m-d');

        try {
            $this->hotels->create($data);
            return 'Hotel cadastrado com sucesso';

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function removeHotel(int $id): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();
        $hotel = $this->hotels->find($id);

        if (!$hotel) {
            throw new HttpException(404, 'Hotel não encontrado');
        }

        try {
            $hotel->delete($hotel);
            return 'Hotel removido com sucesso';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function editHotel(int $id, array $data): string
    {
        $this->userPermissionCheckerService->checkIfUserHasAdminPermission();
        $hotel = $this->hotels->find($id);

        if (!$hotel) {
            throw new HttpException(404, 'Hotel não encontrado');
        }

        try {
            $hotel->fill($data);
            $hotel->save();
            return 'Hotel editado com sucesso';
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function getAuthenticatedUserId()
    {
        // return Auth::user();
        $user = Auth::user();
        return $user->id;
    }
}
