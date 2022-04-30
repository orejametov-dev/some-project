<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ActivityReason;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ActivityReasonRepository
{
//    private ActivityReason|Builder $activityReason;
//
//    public function __construct()
//    {
//        $this->activityReason = ActivityReason::query();
//    }

    /**
     * @param string $type
     * @param int $activity_reason_id
     * @return ActivityReason|Collection|null
     */
    public function getByIdWithType(string $type, int $activity_reason_id): ActivityReason|Collection|null
    {
        return ActivityReason::query()
            ->where('type', $type)
            ->find($activity_reason_id);
    }
}
