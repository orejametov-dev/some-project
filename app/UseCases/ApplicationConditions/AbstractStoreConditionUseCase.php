<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use Carbon\Carbon;

abstract class AbstractStoreConditionUseCase
{
    protected function checkFinishedAtAndStartedAt($started_at , $finished_at): void
    {
        if ($started_at != null && $started_at < Carbon::now()) {
            throw new BusinessException('дата активации не может быть меньше сегоднешнего дня', 'wrong_date', 400);
        }

        if ($finished_at != null && $finished_at <= Carbon::now()) {
            throw new BusinessException('дата деактивации не может быть меньше или равна сегоднешнего дня', 'wrong_date', 400);
        }

        if ($started_at != null && $finished_at != null && $started_at >= $finished_at) {
            throw new BusinessException('дата деактивации не может быть меньше или равна активации', 'wrong_date', 400);
        }
    }
}
