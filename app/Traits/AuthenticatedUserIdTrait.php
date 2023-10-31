<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthenticatedUserIdTrait
{
    public function getUserId(): int
    {
        $user = Auth::user();
        return $user->id;
    }
}
