<?php

declare(strict_types=1);

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class UserIdsFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $user_ids = explode(';', $value);
        $builder->whereIn('user_id', $user_ids);
    }

    public function getBindingName(): string
    {
        return 'user_ids';
    }
}
