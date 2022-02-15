<?php

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;

class AttachTagsProblemCaseUseCase
{
    public function execute(int $id, array $tags_request)
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        $problemCase->tags()->detach();

        $tags = [];
        foreach ($tags_request as $item) {
            $tag = ProblemCaseTag::query()->firstOrCreate(['body' => $item['name'], 'type_id' => $item['type_id']]);
            $tags[] = $tag->id;
        }
        $problemCase->tags()->attach($tags);

        return response()->json($problemCase->load('tags'));
    }
}
