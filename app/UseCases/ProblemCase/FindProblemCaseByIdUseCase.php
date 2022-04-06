<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\NotFoundException;
use App\Models\ProblemCase;

class FindProblemCaseByIdUseCase
{
    public function execute(int $id): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new NotFoundException('Проблемный кейс не найден');
        }

        return $problemCase;
    }
}
