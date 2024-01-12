<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class RoomValidatorService
{
    const AVAILABLE_STATUS_CODE = 1;
    const OCCUPIED_STATUS_CODE = 2;
    const AVAILABLE_STATUS = 'Available';
    const OCCUPIED_STATUS = 'Occupied';

    public function validateUserIsAdminOfHotelRoom(object $user, object $room): void
    {
        if ($user->id != $room->user_id) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Only the hotel administrator user can manipulate data from a hotel room');
        }
    }

    public function validateRoomStatus(object $data): void
    {
        if($data['status'] != self::AVAILABLE_STATUS)
        {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'The hotel room is not available.');
        }
    }
}
