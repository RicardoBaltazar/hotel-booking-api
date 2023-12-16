<?php

namespace App\Services;

use App\Models\Role;
use App\Traits\AuthenticatedUserIdTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPermissionCheckerService
{
    use AuthenticatedUserIdTrait;

    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function checkIfUserHasAdminPermission(): void
    {
        $id = $this->getUserId();
        $roles = $this->role->getByUserId($id);

        if ($roles->role == 'user') {
            throw new HttpException(403, 'Apenas usuários administradores têm permissão para acessar este recurso.');
        }
    }
}
