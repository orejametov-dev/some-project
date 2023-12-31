<?php

declare(strict_types=1);

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class TagsFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $tags = explode(';', $value);

        $builder->whereHas('tags', function ($builder) use ($tags) {
            $builder->whereIn('id', $tags);
        });
    }

    public function getBindingName(): string
    {
        return 'tags';
    }
}
