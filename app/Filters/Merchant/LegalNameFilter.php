<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class LegalNameFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('legal_name', $value);
    }

    public function getBindingName(): string
    {
        return 'legal_name';
    }
}
