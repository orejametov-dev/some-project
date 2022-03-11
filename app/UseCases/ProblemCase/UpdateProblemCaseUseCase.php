<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\ProblemCase;
use Carbon\Carbon;

class UpdateProblemCaseUseCase
{
    public function execute(int $id, Carbon $deadline): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        $problemCase->deadline = $deadline;
        $problemCase->save();

        return $problemCase;
    }
}
