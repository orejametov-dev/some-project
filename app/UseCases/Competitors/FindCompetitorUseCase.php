<?php

namespace App\UseCases\Competitors;

use App\Exceptions\BusinessException;
use App\Models\Competitor;

class FindCompetitorUseCase
{
    public function execute(int $competitor_id): Competitor
    {
        $competitor = Competitor::query()
            ->find($competitor_id);

        if ($competitor === null) {
            throw new BusinessException('Конкурент не найден', 'object_not_found', 404);
        }

        return $competitor;
    }
}
