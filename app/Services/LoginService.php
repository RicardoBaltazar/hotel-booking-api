<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(array $data): string
    {
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('E-mail ou senha inválida');
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return $token;
    }

    public function logout(): string
    {
        Auth::logout();
        return 'logout realizado!';
    }
}
