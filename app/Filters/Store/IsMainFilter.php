<?php

namespace App\Filters\Store;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class IsMainFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('is_main', $value);
    }

    public function getBindingName(): string
    {
        return 'is_main';
    }
}
