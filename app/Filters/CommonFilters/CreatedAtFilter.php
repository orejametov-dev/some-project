<?php

namespace App\Filters\CommonFilters;

use App\Filters\AbstractExactFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CreatedAtFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $date = Carbon::parse($value)->format('Y-m-d');
        $builder->where('created_at', 'LIKE', '%' . $date . '%');
    }

    public function getBindingName(): string
    {
        return 'created_at';
    }
}
