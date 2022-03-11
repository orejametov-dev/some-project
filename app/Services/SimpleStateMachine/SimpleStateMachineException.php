<?php

declare(strict_types=1);

namespace App\Services\SimpleStateMachine;

use Throwable;

class SimpleStateMachineException extends \Exception
{
    private string $error_code;

    /**
     * @param string $message
     * @param string $error_code
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Невозможно переключиться с текущего статуса на указанный.',
        string $error_code = 'STATE_MACHINE_ASSERTION_FAILED',
        int $code = 400,
        Throwable $previous = null
    ) {
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
