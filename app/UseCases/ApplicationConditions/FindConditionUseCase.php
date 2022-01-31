<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Condition;

class FindConditionUseCase
{
    public function execute($condition_id): ?Condition
    {
        $condition = Condition::query()->find($condition_id);

        if ($condition === null) {
            throw new BusinessException('Условие не найдено', 'condition_not_found', 404);
        }

        return $condition;
    }
}
