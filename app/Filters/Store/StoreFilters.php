<?php

namespace App\Filters\Store;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\ActiveFilter;
use App\Filters\CommonFilters\IdFilter;
use App\Filters\CommonFilters\MerchantIdsFilter;
use App\Filters\CommonFilters\StoreIdsFilter;

class StoreFilters extends AbstractFilters
{
    protected array $filters = [
        MerchantIdsFilter::class,
        StoreIdsFilter::class,
        IdFilter::class,
        IsMainFilter::class,
        RegionFilter::class,
        ActiveFilter::class,
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
