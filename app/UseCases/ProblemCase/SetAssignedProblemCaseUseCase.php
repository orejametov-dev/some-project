<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Models\ProblemCase;

class SetAssignedProblemCaseUseCase
{
    public function __construct(
        private FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase
    ) {
    }

    public function execute(int $id, int $assigned_to_id, string $assigned_to_name): ProblemCase
    {
        $problemCase = $this->findProblemCaseByIdUseCase->execute($id);

        $problemCase->assigned_to_id = $assigned_to_id;
        $problemCase->assigned_to_name = $assigned_to_name;
        $problemCase->save();

        return $problemCase;
    }
}
