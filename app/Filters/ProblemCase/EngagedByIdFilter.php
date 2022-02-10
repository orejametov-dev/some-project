<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class EngagedByIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('engaged_by_id', $value);
    }

    public function getBindingName(): string
    {
        return 'engaged_by_id';
    }
}
