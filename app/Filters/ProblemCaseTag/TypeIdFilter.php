<?php

namespace App\Filters\ProblemCaseTag;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class TypeIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('type_id', $value);
    }

    public function getBindingName(): string
    {
        return 'type_id';
    }
}
