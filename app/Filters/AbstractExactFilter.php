<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractExactFilter
{
    /**
     * @param Builder $builder
     * @param mixed $value
     * @return mixed
     */
    abstract public function filter(Builder $builder, $value);

    public function mappings()
    {
        return [];
    }

    protected function resolveFilterValue($key)
    {
        return array_search($key, $this->mappings());
    }
}
