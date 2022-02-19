<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class CreatedAtFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('created_at', $value);
    }

    public function getBindingName(): string
    {
        return 'created_at';
    }
}
