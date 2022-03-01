<?php

namespace App\Filters\Condition;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\ActiveFilter;
use App\Filters\CommonFilters\UpdatedAtFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Merchant\MerchantIdsFilter;
use App\Filters\Store\StoreIdsFilter;

class ConditionFilters extends AbstractFilters
{
    protected array $filters = [
        ConditionIdsFilter::class,
        MerchantIdsFilter::class,
        StoreIdsFilter::class,
        UpdatedAtFilter::class,
        DiscountFilter::class,
        CommissionFilter::class,
        DurationFilter::class,
        ActiveFilter::class,
        MerchantIdFilter::class,
    ];

    protected function getRequestBindings(): array
    {
        $bindings = [];

        foreach ($this->filters as $filterName) {
            $filterBindingName = (new $filterName)->getBindingName();

            $bindings[$filterBindingName] = $filterName;
        }

        return $bindings;
    }
}
