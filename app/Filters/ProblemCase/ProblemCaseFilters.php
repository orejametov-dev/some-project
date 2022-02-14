<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\ClientIdFilter;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\IdFilter;
use App\Filters\CommonFilters\MerchantIdFilter;
use App\Filters\CommonFilters\MerchantIdsFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\CommonFilters\StoreIdsFilter;

class ProblemCaseFilters extends AbstractFilters
{
    protected array $filters = [
        GProblemCaseFilter::class,
        IdFilter::class,
        MerchantIdsFilter::class,
        ClientIdFilter::class,
        CreatedAtFilter::class,
        DateFilter::class,
        StatusIdFilter::class,
        StoreIdsFilter::class,
        AssignedToIdFilter::class,
        CreatedFromNameFilter::class,
        EngagedByIdFilter::class,
        ProblemCaseTagIdFilter::class,
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
