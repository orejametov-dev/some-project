<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class ApiBusinessException extends Exception
{
    protected string $string_code;
    protected ?array $lang;

    public function __construct(string $message = '', string $string_code = '', ?array $lang = null, int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->string_code = $string_code;
        $this->lang = $lang;
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage(), 'code' => $this->string_code, 'lang' => $this->lang], $this->getCode());
    }
}
