<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class ActivityReasonIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->whereHas('merchant_activities', function (Builder $builder) use ($value) {
            $builder->where('activity_reason_id', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'activity_reason_id';
    }
}
