<?php

namespace App\Filters\Store;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class QStoreFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('name', 'like', '%' . $value . '%');
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
