<?php

namespace App\Filters\Complaint;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\IdFilter;

class ComplaintFilters extends AbstractFilters
{
    protected array $filters = [
        IdFilter::class,
        AzoMerchantAccessIdByUserIdFilter::class,
        AzoMerchantAccessByAccessIdFilter::class,
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
