<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    private $user;
    private $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function login(array $data): array
    {
        $user = $this->getUserByEmail($data['email']);
        $this->validateCredentials($data['password'], $user->password);

        $token = $this->generateToken($user);
        $accessLevel = $this->getAccessLevel($user->id);

        return [
            "message" => 'Login bem-sucedido',
            'token' => $token,
            "access" => $accessLevel['role']
        ];
    }

    public function logout(): string
    {
        Auth::logout();
        return 'logout realizado!';
    }

    private function validateCredentials(string $inputPassword, string $hashedPassword): void
    {
        if (!Hash::check($inputPassword, $hashedPassword)) {
            throw new AuthenticationException('E-mail ou senha inválida');
        }
    }

    private function generateToken(User $user): string
    {
        return $user->createToken('token-name')->plainTextToken;
    }

    protected function getUserByEmail(string $email)
    {
        return $this->user->getByEmail($email);
    }

    protected function getAccessLevel(int $userId)
    {
        return $this->role->getByUserId($userId);
    }
}
