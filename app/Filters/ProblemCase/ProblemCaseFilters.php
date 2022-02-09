<?php

namespace App\Filters\ProblemCase;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\IdFilter;

class ProblemCaseFilters extends AbstractFilters
{
    protected array $filters = [
        'q' => ClientFilter::class,
        'id' => IdFilter::class,
    ];
}
