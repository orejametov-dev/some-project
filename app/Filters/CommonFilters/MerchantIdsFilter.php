<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class MerchantIdsFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $merchant_ids = explode(';', $value);
        $builder->whereHas('merchant', function ($builder) use ($merchant_ids) {
            $builder->whereIn('id', $merchant_ids);
        });
    }

    public function getBindingName(): string
    {
        return 'merchant_ids';
    }
}
