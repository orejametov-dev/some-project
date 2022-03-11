<?php

declare(strict_types=1);

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class CreatedByIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('created_by_id', $value);
    }

    public function getBindingName(): string
    {
        return 'created_by_id';
    }
}
