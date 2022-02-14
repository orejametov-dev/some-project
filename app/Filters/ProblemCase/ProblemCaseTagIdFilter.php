<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class ProblemCaseTagIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->whereHas('tags', function ($query) use ($value) {
            $query->where('problem_case_tag_id', $value);
        });
    }

    public function getBindingName(): string
    {
        return 'tag_id';
    }
}
