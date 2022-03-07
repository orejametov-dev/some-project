<?php

namespace App\Filters\AdditionalAgreement;

use App\Filters\AbstractFilters;
use App\Filters\Merchant\MerchantIdFilter;

class AdditionalAgreementFilters extends AbstractFilters
{
    protected array $filters = [
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
