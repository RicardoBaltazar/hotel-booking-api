<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class RoomValidatorService
{
    const FORBIDDEN_MESSAGE  = 'Only the hotel administrator user can manipulate data from a hotel room';

    public function validateUserIsAdminOfHotelRoom(object $user, object $room): void
    {
        if ($user->id != $room->user_id) {
            throw new HttpException(Response::HTTP_FORBIDDEN, self::FORBIDDEN_MESSAGE);
        }
    }
}
