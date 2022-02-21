<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class MerchantIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('merchant_id', $value);
    }

    public function getBindingName(): string
    {
        return 'merchant_id';
    }
}
