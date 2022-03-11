<?php

declare(strict_types=1);

namespace App\Filters\Condition;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class DurationFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('duration', $value);
    }

    public function getBindingName(): string
    {
        return 'duration';
    }
}
