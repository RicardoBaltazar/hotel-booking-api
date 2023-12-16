<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthenticatedUserHandlerService
{
    public function getAuthenticatedUser()
    {
        return Auth::user();
    }
}
