<?php

namespace App\Modules\Merchants\Traits;

trait ProblemCaseStatuses
{
    public function isStatusNew()
    {
        return $this->status_id == self::NEW;
    }

    public function isStatusInProcess()
    {
        return $this->status_id == self::IN_PROCESS;
    }

    public function isStatusDone()
    {
        return $this->status_id == self::DONE;
    }

    public function isStatusFinished()
    {
        return $this->status_id == self::FINISHED;
    }

    public function scopeNew($builder)
    {
        return $builder->where('status_id', self::NEW);
    }

    public function scopeInProcess($builder)
    {
        return $builder->where('status_id', self::IN_PROCESS);
    }

    public function scopeDone($builder)
    {
        return $builder->where('status_id', self::DONE);
    }

    public function scopeFinished($builder)
    {
        return $builder->where('status_id', self::FINISHED);
    }

    public function setStatusNew()
    {
        $status = self::getOneById(self::NEW);
        $this->status_updated_at = now();
        $this->status_id = $status->id;
        $this->status_key = $status->name;
    }

    public function setStatusInProcess()
    {
        return $this->setStatus(self::IN_PROCESS);
    }

    public function setStatusDone()
    {
        return $this->setStatus(self::DONE);
    }

    public function setStatusFinished()
    {
        return $this->setStatus(self::FINISHED);
    }

    public function setStatus(int $status_id)
    {
        $status = self::getOneById($status_id);
        $this->assertStateSwitchTo($status_id);
        $this->status_updated_at = now();
        $this->status_id = $status_id;
        $this->status_key = $status->name;
    }
}
