<?php

namespace App\Services\SimpleStateMachine;

interface SimpleStateMachinable
{
    public function getStateAttribute(): ?int;

    public function getSimpleStateMachineMap(): array;
}
