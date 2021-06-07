<?php


namespace App\Services\SimpleStateMachine;


interface SimpleStateMachinable
{
    public function getStateAttribute();

    public function getSimpleStateMachineMap(): array;
}
