<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class StatusIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('status_id', $value);
    }

    public function getBindingName(): string
    {
        return 'status_id';
    }
}
