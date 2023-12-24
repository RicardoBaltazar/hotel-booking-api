<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HotelValidatorService
{
    public function validateIfIsAdminOfHotel(object $user, object $data): void
    {
        if ($user->id != $data->user_id) {
            throw new HttpException(403, 'Only the hotel administrator user can register a new room');
        }
    }
}
