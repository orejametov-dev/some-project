<?php

namespace App\Services\SimpleStateMachine;

use Throwable;

class SimpleStateMachineException extends \Exception
{
    private $error_code;

    public function __construct(
        $message = 'Невозможно переключиться с текущего статуса на указанный.',
        $error_code = 'STATE_MACHINE_ASSERTION_FAILED',
        $code = 400,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->error_code = $error_code;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->error_code;
    }
}
