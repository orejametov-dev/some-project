<?php

namespace App\Filters\Tag;

class TagFilters extends \App\Filters\AbstractFilters
{
    protected array $filters = [
        QTagFilter::class,
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
