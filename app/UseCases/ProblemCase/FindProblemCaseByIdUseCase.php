<?php

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\ProblemCase;

class FindProblemCaseByIdUseCase
{
    public function execute(int $problem_case_id): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($problem_case_id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        return $problemCase;
    }
}
