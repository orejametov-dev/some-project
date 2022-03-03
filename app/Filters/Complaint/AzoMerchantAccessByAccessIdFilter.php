<?php

namespace App\Filters\Complaint;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class AzoMerchantAccessByAccessIdFilter extends AbstractExactFilter
{

    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('azo_merchant_access_id', $value);
    }

    public function getBindingName(): string
    {
        return 'azo_merchant_access_id';
    }
}
