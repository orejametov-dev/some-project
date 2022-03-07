<?php

namespace App\Filters\ProblemCaseTag;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class QProblemCaseTagFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('body', 'LIKE', '%' . $value . '%');
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
