<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class MaintainerIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('maintainer_id', $value);
    }

    public function getBindingName(): string
    {
        return 'maintainer_id';
    }
}
