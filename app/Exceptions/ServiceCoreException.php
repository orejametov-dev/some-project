<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ServiceCoreException extends Exception
{

    private $status_code = 'unhandled_error';
    private $responseCode;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseCode = $code;
        if ($this->code == 401 || $this->code == 403) {
            $this->status_code = 'error_with_auth';
            $this->responseCode = 400;
        }
        if ($this->code == 404) {
            $this->status_code = 'object_not_found';
        }
        if ($this->code == 400) {
            $this->status_code = 'business_exception';
        }
        if ($this->code == 422) {
            $this->status_code = 'unprocessable_entity';
        }
        if ($this->code == 500) {
            $this->status_code = 'server_error';
        }
    }

    public function render()
    {
        $merged = json_decode($this->message, true) ?? [];
        return response()->json(
            array_merge(['status' => $this->code, 'code' => $this->status_code], $merged),
            $this->responseCode
        );
    }

}
