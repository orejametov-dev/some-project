<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\NotFoundException;
use App\Models\Condition;

class FindConditionByIdUseCase
{
    public function execute(int $condition_id): Condition
    {
        $condition = Condition::query()->find($condition_id);

        if ($condition === null) {
            throw new NotFoundException('Условие не найдено');
        }

        return $condition;
    }
}
