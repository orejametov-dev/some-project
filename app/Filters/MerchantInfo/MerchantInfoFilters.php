<?php

namespace App\Filters\MerchantInfo;

use App\Filters\AbstractFilters;
use App\Filters\Merchant\MerchantIdsFilter;

class MerchantInfoFilters extends AbstractFilters
{
    protected array $filters = [
        MerchantIdsFilter::class,
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
