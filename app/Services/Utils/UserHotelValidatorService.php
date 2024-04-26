<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class UserHotelValidatorService
{
    public function ensureUserIsHotelAdmin(object $user, object $hotel): void
    {
        if ($user->id !== $hotel->user_id) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Only the hotel administrator user can register a new room');
        }
    }
}
