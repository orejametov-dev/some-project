<?php

namespace App\Filters\Tag;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class GTagFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('title', 'LIKE', '%' . $value . '%');
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
