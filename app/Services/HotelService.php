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
        $data['user_id'] = $this->checkIfUserHasAdminPermission();
        $data['release_date'] = date('Y-m-d');

        try {
            $this->hotels->create($data);
            return 'Hotel cadastrado com sucesso';

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }

    private function checkIfUserHasAdminPermission(): int
    {
        $id = $this->getUserId();
        $roles = $this->role->getByUserId($id);

        if($roles->role == 'user')
        {
            throw new HttpException(403, 'Apenas usuários administradores podem cadastrar Hoteis');
        }

        return $id;
    }
}
