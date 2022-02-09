<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class IdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }
}
