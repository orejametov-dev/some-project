<?php

namespace App\Filters\Notification;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class GNotificationFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        $builder->where(function ($builder) use ($value) {
            $builder->where('title_uz', 'LIKE', '%' . $value . '%')
                ->orWhere('title_ru', 'LIKE', '%' . $value . '%');
        });
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
