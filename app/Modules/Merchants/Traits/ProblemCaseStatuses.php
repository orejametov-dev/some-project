<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Traits;

use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Database\Eloquent\Builder;

trait ProblemCaseStatuses
{
    public function isStatusNew(): bool
    {
        return $this->status_id == self::NEW;
    }

    public function isStatusInProcess(): bool
    {
        return $this->status_id == self::IN_PROCESS;
    }

    public function isStatusDone(): bool
    {
        return $this->status_id == self::DONE;
    }

    public function isStatusFinished(): bool
    {
        return $this->status_id == self::FINISHED;
    }

    public function scopeNew(Builder $builder): Builder
    {
        return $builder->where('status_id', self::NEW);
    }

    public function scopeInProcess(Builder $builder): Builder
    {
        return $builder->where('status_id', self::IN_PROCESS);
    }

    public function scopeDone(Builder $builder): Builder
    {
        return $builder->where('status_id', self::DONE);
    }

    public function scopeFinished(Builder $builder): Builder
    {
        return $builder->where('status_id', self::FINISHED);
    }

    public function setStatusNew(): ProblemCase
    {
        $status = self::getOneById(self::NEW);
        $this->status_updated_at = now();
        $this->status_id = $status->id;
        $this->status_key = $status->name;

        return $this;
    }

    public function setStatusInProcess(): self
    {
        return $this->setStatus(self::IN_PROCESS);
    }

    public function setStatusDone(): self
    {
        return $this->setStatus(self::DONE);
    }

    public function setStatusFinished(): self
    {
        return $this->setStatus(self::FINISHED);
    }

    public function setStatus(int $status_id): ProblemCase
    {
        $status = self::getOneById($status_id);
        $this->assertStateSwitchTo($status_id);
        $this->status_updated_at = now();
        $this->status_id = $status_id;
        $this->status_key = $status->name;

        return $this;
    }
}
