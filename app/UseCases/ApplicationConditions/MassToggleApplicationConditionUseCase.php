<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use App\Modules\Merchants\Models\Condition;
use Carbon\Carbon;

class MassToggleApplicationConditionUseCase
{
    public function execute(bool $active = true): void
    {
        $conditionQuery = Condition::query()
            ->whereHas('merchant', function ($query) {
                $query->where('active', true);
            });

        if ($active === true) {
            $conditionQuery->where('started_at', Carbon::now()->format('Y-m-d'))
                ->update(['active' => true]);
        } else {
            $conditionQuery->where('finished_at', Carbon::now()->format('Y-m-d'))
                ->update(['active' => false]);
        }
    }
}
