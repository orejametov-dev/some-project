<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\Exceptions\BusinessException;
use App\Models\ProblemCase;
use App\Models\ProblemCaseTag;

class AttachTagsProblemCaseUseCase
{
    public function __construct(
        private FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase
    ) {
    }

    /**
     * @throws BusinessException
     */
    public function execute(int $id, array $tags_request): ProblemCase
    {
        $problemCase = $this->findProblemCaseByIdUseCase->execute($id);
        $problemCase->tags()->detach();

        $tags = [];
        foreach ($tags_request as $item) {
            $tag = ProblemCaseTag::query()
                ->where('body', $item['name'])
                ->where('type_id', $item['type_id'])
                ->first();
            if ($tag === null) {
                $tag = new ProblemCaseTag();
                $tag->body = $item['name'];
                $tag->type_id = $item['type_id'];
                $tag->save();
            }

            $tags[] = $tag->id;
        }

        $problemCase->tags()->attach($tags);

        return $problemCase->load('tags');
    }
}
