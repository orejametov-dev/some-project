<?php

namespace App\Filters\Condition;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\MerchantIdFilter;
use App\Filters\CommonFilters\StoreIdFilter;

class ConditionFilters extends AbstractFilters
{
    protected array $filters = [
        MerchantIdFilter::class,
        StoreIdFilter::class,
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
