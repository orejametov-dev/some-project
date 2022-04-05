<?php

namespace App\Exceptions;

use App\Enums\ExceptionEnum;
use Throwable;

class BadRequestException extends AbstractException
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, ExceptionEnum::STRING_CODE_BAD_REQUEST()->getValue(), ExceptionEnum::CODE_BAD_REQUEST()->getValue(), $previous);
    }
}
