<?php

namespace App\Filters\Store;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class RegionFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('region', $value);
    }

    public function getBindingName(): string
    {
        return 'region';
    }
}
