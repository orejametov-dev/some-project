<?php

declare(strict_types=1);

namespace App\Services\SimpleStateMachine;

interface SimpleStateMachinable
{
    public function getStateAttribute(): ?int;

    public function getSimpleStateMachineMap(): array;
}
