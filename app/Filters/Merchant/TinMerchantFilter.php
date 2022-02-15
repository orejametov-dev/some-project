<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class TinMerchantFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->whereHas('merchant_info', function ($builder) use ($value) {
            $builder->where('tin', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'tin';
    }
}
