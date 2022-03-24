<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Models\ProblemCase;
use App\Models\ProblemCaseTag;

class NewAttachTagsProblemCaseUseCase
{
    public function __construct(
        private FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase
    ) {
    }

    public function execute(int $id, array $tags_request): ProblemCase
    {
        $problemCase = $this->findProblemCaseByIdUseCase->execute($id);

        $tags = ProblemCaseTag::query()
            ->whereIn('id', $tags_request)
            ->get();

        $problemCase->tags()->detach();

        if (array_diff($tags_request, $tags->pluck('id')->toArray()) != null) {
            throw new BusinessException('Тег не существует', 'problem_case_not_exists', 400);
        }

        $problemCase->tags()->attach($tags);

        return $problemCase->load('tags');
    }
}
