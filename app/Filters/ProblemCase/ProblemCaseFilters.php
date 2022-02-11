<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\ClientIdFilter;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\IdFilter;
use App\Filters\CommonFilters\MerchantIdFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\CommonFilters\StoreIdFilter;

class ProblemCaseFilters extends AbstractFilters
{
    protected array $filters = [
        GProblemCaseFilter::class,
        IdFilter::class,
        MerchantIdFilter::class,
        ClientIdFilter::class,
        CreatedAtFilter::class,
        DateFilter::class,
        StatusIdFilter::class,
        StoreIdFilter::class,
        AssignedToIdFilter::class,
        CreatedFromNameFilter::class,
        EngagedByIdFilter::class,
        ProblemCaseTagIdFilter::class,
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
