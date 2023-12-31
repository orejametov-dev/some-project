<?php

declare(strict_types=1);

namespace App\Filters\AzoMerchantAccess;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\StoreIdsFilter;
use App\Filters\CommonFilters\UserIdsFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Merchant\MerchantIdsFilter;

class AzoMerchantAccessFilters extends AbstractFilters
{
    protected array $filters = [
        QAzoMerchantAccessFilter::class,
        DateFilter::class,
        MerchantIdsFilter::class,
        StoreIdsFilter::class,
        UserIdsFilter::class,
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
