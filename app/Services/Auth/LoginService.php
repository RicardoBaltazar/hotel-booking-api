<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    private $user;
    private $role;

    public function __construct(
        User $user,
        Role $role
        )
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function login(array $data): array
    {
        $user = $this->user->getByEmail($data['email']);
        $this->validateCredentials($data['password'], $user->password);

        $token = $user->createToken('token-name')->plainTextToken;
        $accessLevel = $this->role->getByUserId($user->id);

        return [
            "message" => 'Login bem-sucedido',
            'token' => $token,
            "access" => $accessLevel['role']
        ];
    }

    protected function getUserByEmail(string $email)
    {
        return $this->user->getByEmail($email);
    }

    private function validateCredentials(string $inputPassword, string $hashedPassword): void
    {
        if (!Hash::check($inputPassword, $hashedPassword)) {
            throw new AuthenticationException('E-mail ou senha inválida');
        }
    }
}
