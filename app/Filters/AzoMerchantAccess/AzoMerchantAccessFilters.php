<?php

namespace App\Filters\AzoMerchantAccess;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\UserIdsFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Merchant\MerchantIdsFilter;
use App\Filters\Store\StoreIdsFilter;

class AzoMerchantAccessFilters extends AbstractFilters
{
    protected array $filters = [
        GAzoMerchantAccessFilter::class,
        DateFilter::class,
        MerchantIdsFilter::class,
        StoreIdsFilter::class,
        UserIdsFilter::class,
        MerchantIdFilter::class
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
