<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\Utils\CredentialsValidator;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    private $credentialsValidator;
    private $user;
    private $role;

    public function __construct(
        CredentialsValidator $credentialsValidator,
        User $user,
        Role $role
        )
    {
        $this->credentialsValidator = $credentialsValidator;
        $this->user = $user;
        $this->role = $role;
    }

    public function login(array $data): array
    {
        $user = $this->user->getByEmail($data['email']);
        $this->credentialsValidator->validateCredentials($data['password'], $user->password);

        $token = $user->createToken('token-name')->plainTextToken;
        $accessLevel = $this->role->getByUserId($user->id);

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

    protected function getUserByEmail(string $email)
    {
        return $this->user->getByEmail($email);
    }
}
