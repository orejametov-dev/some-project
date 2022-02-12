<?php

namespace App\Filters\Notification;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class MerchantIdNotificationFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->whereHas('stores', function ($builder) use ($value) {
            $builder->where('stores.merchant_id', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'merchant_id';
    }
}
