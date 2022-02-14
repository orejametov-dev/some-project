<?php

namespace App\Filters\MerchantRequest;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\StatusIdFilter;

class MerchantRequestFilters extends AbstractFilters
{
    protected array $filters = [
        GMerchantRequestFilter::class,
        StatusIdFilter::class,
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
