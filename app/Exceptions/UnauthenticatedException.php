<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ExceptionEnum;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnauthenticatedException extends AbstractException
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getStringCode()
    {
        return ExceptionEnum::STRING_CODE_UNAUTHENTICATED()->getValue();
    }
}
