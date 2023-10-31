<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use RuntimeException;

class CustomException extends RuntimeException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function render()
    {
        return response()->json($this->getMessage(), Response::HTTP_NOT_FOUND);
    }
}
