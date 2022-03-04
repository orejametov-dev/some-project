<?php

namespace App\Filters\ProblemCaseTag;

use App\Filters\AbstractFilters;

class ProblemCaseTagFilters extends AbstractFilters
{
    protected array $filters = [
        QProblemCaseTagFilter::class,
        TypeIdFilter::class,
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
