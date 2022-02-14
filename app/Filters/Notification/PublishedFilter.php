<?php

namespace App\Filters\Notification;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class PublishedFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value): void
    {
        if ($value == true) {
            $builder->where('start_schedule', '<=', now())
                ->where('end_schedule', '>=', now());
        }

        if ($value == false) {
            $builder->where('end_schedule', '<=', now());
        }
    }

    public function getBindingName(): string
    {
        return 'published';
    }
}
