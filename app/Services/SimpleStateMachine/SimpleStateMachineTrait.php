<?php

namespace App\Services\SimpleStateMachine;

trait SimpleStateMachineTrait
{
    abstract public function getSimpleStateMachineMap(): array;

    /**
     * @param int $state_to
     * @return bool
     * @throws SimpleStateMachineException
     */
    private function assertStateSwitchTo(int $state_to): bool
    {
        $attr = $this->getStateAttribute();
        $map = $this->getSimpleStateMachineMap();
        if (!is_array($map) || empty($map)) {
            throw new \InvalidArgumentException('State machine mapper getSimpleStateMachineMap() must be set');
        }
        if (!isset($attr)) {
            throw new \InvalidArgumentException('Property in getStateAttribute() must be set');
        }
        if (!array_key_exists($attr, $map)) {
            throw new \InvalidArgumentException('State key does not exist in getSimpleStateMachineMap()');
        }
        if (!in_array($state_to, $map[$attr])) {
            throw new SimpleStateMachineException();
        }

        return true;
    }
}
