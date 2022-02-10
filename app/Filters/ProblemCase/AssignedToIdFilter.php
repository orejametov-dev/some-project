<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class AssignedToIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('assigned_to_id', $value);
    }

    public function getBindingName(): string
    {
        return 'assigned_to_id';
    }
}
