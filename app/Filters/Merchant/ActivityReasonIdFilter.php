<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ActivityReasonIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->whereHas('merchant_activities', function (Builder $builder) use ($value) {
            $builder->join(
                DB::raw('(select merchant_activities.merchant_id as merchant_id, max(merchant_activities.id) as max_id from merchant_activities group by merchant_id) as sub_query'),
                'sub_query.merchant_id',
                '=',
                'merchants.id'
            )
                ->whereRaw('sub_query.max_id = merchant_activities.id')
                ->where('merchant_activities.activity_reason_id', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'activity_reason_id';
    }
}
