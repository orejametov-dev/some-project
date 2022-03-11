<?php

declare(strict_types=1);

namespace App\Filters\Complaint;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class AzoMerchantAccessIdByUserIdFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where('azo_merchant_access_id', $value);
    }

    public function getBindingName(): string
    {
        return 'user_id';
    }
}
