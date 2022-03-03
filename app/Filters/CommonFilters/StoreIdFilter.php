<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class StoreIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('store_id', $value);
    }

    public function getBindingName(): string
    {
        return 'store_id';
    }
}
