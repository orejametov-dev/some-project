<?php

namespace App\Filters\Condition;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class ConditionIdsFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $condition_ids = explode(';', $value);
        $builder->whereIn('id', $condition_ids);
    }

    public function getBindingName(): string
    {
        return 'condition_ids';
    }
}
