<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiBusinessException extends Exception
{
    protected $string_code;
    protected $lang;

    public function __construct($message = "", $string_code = '', $lang = null, $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->string_code = $string_code;
        $this->lang = $lang;
    }

    public function render()
    {
        return response()->json(['message' => $this->getMessage(), 'code' => $this->string_code , 'lang' => $this->lang], $this->getCode());
    }
}
