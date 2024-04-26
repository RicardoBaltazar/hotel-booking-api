<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserHandlerService
{
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }
}
