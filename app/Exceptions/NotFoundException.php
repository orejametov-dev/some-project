<?php

namespace App\Exceptions;

use App\Enums\ExceptionEnum;
use Throwable;

class NotFoundException extends AbstractException
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, ExceptionEnum::STRING_CODE_NOT_FOUND()->getValue(), ExceptionEnum::CODE_NOT_FOUND()->getValue(), $previous);
    }
}
