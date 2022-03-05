<?php

namespace App\Filters\AzoMerchantAccess;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class QAzoMerchantAccessFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where(function ($builder) use ($value) {
            $builder->where('user_name', 'LIKE', '%' . $value . '%')
                ->orWhere('phone', 'LIKE', '%' . $value . '%');
        });
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
