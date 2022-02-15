<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DateFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $date = Carbon::parse($value);
        $builder->whereDate('created_at', $date);
    }

    public function getBindingName(): string
    {
        return 'date';
    }
}
