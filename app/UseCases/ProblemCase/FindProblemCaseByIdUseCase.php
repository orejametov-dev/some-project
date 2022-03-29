<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Models\ProblemCase;

class FindProblemCaseByIdUseCase
{
    public function execute(int $id): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        return $problemCase;
    }
}
