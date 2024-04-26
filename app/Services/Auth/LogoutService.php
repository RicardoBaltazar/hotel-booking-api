<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function logout(): string
    {
        Auth::logout();
        return 'logout realizado!';
    }
}
