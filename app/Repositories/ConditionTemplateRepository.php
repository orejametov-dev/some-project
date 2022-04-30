<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ConditionTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ConditionTemplateRepository
{
//    private ConditionTemplate|Builder $conditionTemplate;
//
//    public function __construct()
//    {
//        $this->conditionTemplate = ConditionTemplate::query();
//    }

    /**
     * @param array $condition_template_ids
     * @return ConditionTemplate[]|Collection
     */
    public function getByIds(array $condition_template_ids): ConditionTemplate|Collection
    {
        return ConditionTemplate::query()
            ->whereIn('id', $condition_template_ids)
            ->get();
    }
}
