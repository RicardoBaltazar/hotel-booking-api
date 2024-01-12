<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class DateValidator
{
    public function validateDate(object $dateProvided)
    {
        $currentDate = new \DateTime();

        if ($dateProvided < $currentDate) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'The date provided is in the past, we cannot schedule dates that have passed.');
        }
    }
}
