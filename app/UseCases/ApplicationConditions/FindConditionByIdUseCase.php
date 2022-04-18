<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\NotFoundException;
use App\Models\Condition;
use App\Repositories\ApplicationConditionRepository;

class FindConditionByIdUseCase
{
    public function __construct(
        private ApplicationConditionRepository $applicationConditionRepository
    ) {
    }

    public function execute(int $condition_id): Condition
    {
        $condition = $this->applicationConditionRepository->getById($condition_id);

        if ($condition === null) {
            throw new NotFoundException('Условие не найдено');
        }

        return $condition;
    }
}
