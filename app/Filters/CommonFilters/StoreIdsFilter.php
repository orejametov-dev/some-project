<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class StoreIdsFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $store_ids = explode(';', $value);
        $builder->whereIn('id', $store_ids);
    }

    public function getBindingName(): string
    {
        return 'store_ids';
    }
}
