<?php

namespace App\UseCases\Competitors;

use App\Exceptions\NotFoundException;
use App\Models\Competitor;

class FindCompetitorUseCase
{
    public function execute(int $competitor_id): Competitor
    {
        $competitor = Competitor::query()
            ->find($competitor_id);

        if ($competitor === null) {
            throw new NotFoundException('Конкурент не найден');
        }

        return $competitor;
    }
}
