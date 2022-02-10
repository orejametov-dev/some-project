<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractExactFilter
{
    abstract public function filter(Builder $builder, mixed $value): void;

    abstract public function getBindingName(): string;
}
