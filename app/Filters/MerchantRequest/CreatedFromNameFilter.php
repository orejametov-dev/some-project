<?php

namespace App\Filters\MerchantRequest;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class CreatedFromNameFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('created_from_name', $value);
    }

    public function getBindingName(): string
    {
        return 'created_from_name';
    }
}
