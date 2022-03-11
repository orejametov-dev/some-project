<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\ProblemCase;

class SetAssignedProblemCaseUseCase
{
    public function execute(int $id, int $assigned_to_id, string $assigned_to_name): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        $problemCase->assigned_to_id = $assigned_to_id;
        $problemCase->assigned_to_name = $assigned_to_name;
        $problemCase->save();

        return $problemCase;
    }
}
