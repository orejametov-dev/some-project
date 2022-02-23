<?php

namespace App\Filters\AzoMerchantAccess;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\MerchantIdsFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\UserIdsFilter;

class AzoMerchantAccessFilters extends AbstractFilters
{
    protected array $filters = [
        GAzoMerchantAccessFilter::class,
        DateFilter::class,
        MerchantIdsFilter::class,
        StoreIdFilter::class,
        UserIdsFilter::class,
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
