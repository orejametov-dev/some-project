<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\ActiveFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\IdFilter;
use App\Filters\CommonFilters\MerchantIdsFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\CommonFilters\TagsFilter;

class MerchantFilters extends AbstractFilters
{
    protected array $filters = [
        GMerchantFilter::class,
        MerchantIdsFilter::class,
        IdFilter::class,
        TagsFilter::class,
        LegalNameFilter::class,
        DateFilter::class,
        MaintainerIdFilter::class,
        RegionMerchantFilter::class,
        TokenFilter::class,
        StatusIdFilter::class,
        ActiveFilter::class,
        TinMerchantFilter::class,
        SpecialMerchantIdFilter::class,
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
