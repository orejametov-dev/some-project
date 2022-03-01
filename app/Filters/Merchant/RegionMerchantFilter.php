<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class RegionMerchantFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $value = explode(';', $value);

        $builder->whereHas('stores', function ($builder) use ($value) {
            $builder->whereIn('region', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'region';
    }
}
