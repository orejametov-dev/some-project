<?php

namespace App\Modules\Merchants\DTO\ProblemCases;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCaseDTO
{
    public function __construct(
        public string  $created_from_name,
        public ?string $description,
        public string|int  $identifier,
        public int $user_id,
        public string $user_name
    )
    {
    }
}
