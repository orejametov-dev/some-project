<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

abstract class AbstractException extends Exception
{
    abstract public function getStatus();

    abstract public function getStringCode();

    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $this->getStatus(), $previous);
    }

    public function render(): JsonResponse
    {
        return new JsonResponse(
            [
            'message' => $this->getMessage(),
            'code' => $this->getStringCode(),
        ],
            $this->getStatus()
        );
    }
}
