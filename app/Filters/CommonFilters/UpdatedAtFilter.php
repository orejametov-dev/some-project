<?php

declare(strict_types=1);

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class UpdatedAtFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('updated_at', $value);
    }

    public function getBindingName(): string
    {
        return 'updated_at';
    }
}
