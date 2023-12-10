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

    private $role;
    private $hotels;

    public function __construct(
        Role $role,
        Hotels $hotels
    )
    {
        $this->role = $role;
        $this->hotels = $hotels;
    }

    public function createHotel(array $data)
    {
        $this->checkIfUserHasAdminPermission();
        $data['user_id'] = $this->getUserId();
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
        $this->checkIfUserHasAdminPermission();
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
        $this->checkIfUserHasAdminPermission();
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


    private function checkIfUserHasAdminPermission(): void
    {
        $id = $this->getUserId();
        $roles = $this->role->getByUserId($id);

        if($roles->role == 'user')
        {
            throw new HttpException(403, 'Apenas usuários administradores têm permissão para acessar este recurso.');
        }
    }
}
