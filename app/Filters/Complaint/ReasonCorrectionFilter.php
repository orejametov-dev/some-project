<?php

namespace App\Filters\Complaint;

use App\Filters\AbstractExactFilter;
use Illuminate\Database\Eloquent\Builder;

class ReasonCorrectionFilter extends AbstractExactFilter
{

    public function filter(Builder $builder, mixed $value): void
    {
        // TODO: Implement filter() method.
    }

    public function getBindingName(): string
    {
        // TODO: Implement getBindingName() method.
    }
}
