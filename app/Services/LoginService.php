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
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('E-mail ou senha inválida');
        }

        $token = $this->generateToken($user);
        $accessLevel = $this->role->getByUserId($user->id);

        return [
            "message" => 'Login bem-sucedido',
            'token' => $token,
            "access" => $accessLevel->first()->role
        ];
    }

    public function logout(): string
    {
        Auth::logout();
        return 'logout realizado!';
    }

    private function generateToken(User $user): string
    {
        return $user->createToken('token-name')->plainTextToken;
    }
}
