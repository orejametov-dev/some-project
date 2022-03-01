<?php

namespace App\Filters\Merchant;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class GMerchantFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where(function ($builder) use ($value) {
            $builder->where('legal_name', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%');
        });

        if (is_numeric($value)) {
            $builder->orWhereHas('merchant_info', function (Builder $builder) use ($value) {
                $builder->Where('tin', $value)
                    ->orWhere('contract_number', $value);
            });
        }
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
