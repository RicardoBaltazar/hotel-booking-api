<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class ModelValidatorService
{
    public function validateIfModelHasRecords(object|null $model, string $descrition): void
    {
        if (!$model) {
            throw new HttpException(Response::HTTP_NOT_FOUND, $descrition);
        }
    }
}
