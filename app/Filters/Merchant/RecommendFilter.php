<?php

declare(strict_types=1);

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class RecommendFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('recommend', $value);
    }

    public function getBindingName(): string
    {
        return 'recommend';
    }
}
