<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class GProblemCaseFilter extends AbstractExactFilter
{
    public function filter(Builder $builder, mixed $value) : void
    {
        collect(explode(' ', $value))->filter()->each(function ($value) use ($builder) {
            $value = '%' . $value . '%';

            $builder->where(function ($builder) use ($value) {
                $builder->where('client_name', 'like', $value)
                        ->orWhere('client_surname', 'like', $value)
                        ->orWhere('client_patronymic', 'like', $value)
                        ->orWhere('phone', 'like', $value);
            });
        });
    }

    public function getBindingName(): string
    {
        return 'q';
    }
}
