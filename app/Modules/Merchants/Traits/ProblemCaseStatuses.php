<?php


namespace App\Modules\Merchants\Traits;



use App\Modules\Merchants\Services\ProblemCaseStatus;

trait ProblemCaseStatuses
{
    public function isStatusNew()
    {
        return $this->status_id == ProblemCaseStatus::NEW;
    }

    public function isStatusInProcess()
    {
        return $this->status_id == ProblemCaseStatus::IN_PROCESS;
    }

    public function isStatusDone()
    {
        return $this->status_id == ProblemCaseStatus::DONE;
    }

    public function isStatusFinished()
    {
        return $this->status_id == ProblemCaseStatus::FINISHED;
    }

    public function scopeNew($builder)
    {
        return $builder->where('status_id', ProblemCaseStatus::NEW);
    }

    public function scopeInProcess($builder)
    {
        return $builder->where('status_id', ProblemCaseStatus::IN_PROCESS);
    }

    public function scopeDone($builder)
    {
        return $builder->where('status_id', ProblemCaseStatus::DONE);
    }

    public function scopeFinished($builder)
    {
        return $builder->where('status_id', ProblemCaseStatus::FINISHED);
    }

    public function setStatusNew()
    {
        return $this->setStatus(ProblemCaseStatus::NEW);
    }

    public function setStatusInProcess()
    {
        return $this->setStatus(ProblemCaseStatus::IN_PROCESS);
    }

    public function setStatusDone()
    {
        return $this->setStatus(ProblemCaseStatus::DONE);
    }

    public function setStatusFinished()
    {
        return $this->setStatus(ProblemCaseStatus::FINISHED);
    }

    public function setStatus(int $status_id)
    {
        $status = ProblemCaseStatus::getOneById($status_id);
        $this->status_updated_at = now();
        $this->status_id = $status_id;
        $this->status_key = $status->name;
    }

}
