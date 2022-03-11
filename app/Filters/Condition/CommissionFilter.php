<?php

declare(strict_types=1);

namespace App\Filters\Condition;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class CommissionFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('commission', $value);
    }

    public function getBindingName(): string
    {
        return 'commission';
    }
}
