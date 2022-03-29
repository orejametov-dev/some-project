<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Models\ProblemCase;
use Carbon\Carbon;

class UpdateProblemCaseUseCase
{
    public function __construct(
        private FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase
    ) {
    }

    public function execute(int $id, Carbon $deadline): ProblemCase
    {
        $problemCase = $this->findProblemCaseByIdUseCase->execute($id);
        $problemCase->deadline = $deadline;
        $problemCase->save();

        return $problemCase;
    }
}
