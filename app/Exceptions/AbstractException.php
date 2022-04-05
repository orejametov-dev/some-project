<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class AbstractException extends Exception
{
    protected string $string_code;

    public function __construct(string $message = '', string $string_code = '', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->string_code = $string_code;
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage(), 'code' => $this->string_code], $this->getCode());
    }
}
