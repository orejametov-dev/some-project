<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class BusinessException extends Exception
{
    protected $string_code;

    public function __construct($message = '', $string_code = '', $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->string_code = $string_code;
    }

    public function render()
    {
        return response()->json(['message' => $this->getMessage(), 'code' => $this->string_code], $this->getCode());
    }
}
