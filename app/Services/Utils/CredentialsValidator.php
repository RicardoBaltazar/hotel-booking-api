<?php

namespace App\Services\Utils;

use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class CredentialsValidator
{
    public static function validateCredentials(string $inputPassword, string $hashedPassword): void
    {
        if (!Hash::check($inputPassword, $hashedPassword)) {
            throw new AuthenticationException('E-mail ou senha inválida');
        }
    }
}
