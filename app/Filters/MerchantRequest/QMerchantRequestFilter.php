<?php

namespace App\Filters\MerchantRequest;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class QMerchantRequestFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where(function ($builder) use ($value) {
            $builder->where('name', 'like', '%' . $value . '%')
                ->orWhere('information', 'like', '%' . $value . '%')
                ->orWhere('legal_name', 'like', '%' . $value . '%')
                ->orWhere('user_name', 'like', '%' . $value . '%')
                ->orWhere('user_phone', 'like', '%' . $value . '%');
        });
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
