<?php

namespace App\Services\Utils;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ModelValidatorService
{
    public function validateIfModelHasRecords(object|null $model, string $descrition, int $httpStatus): void
    {
        if (!$model) {
            throw new HttpException($httpStatus, $descrition);
        }
    }
}
