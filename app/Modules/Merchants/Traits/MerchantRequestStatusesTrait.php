<?php


namespace App\Modules\Merchants\Traits;

trait MerchantRequestStatusesTrait
{
    public function isStatusNew()
    {
        return $this->status_id == self::NEW;
    }

    public function isInProcess()
    {
        return $this->status_id == self::IN_PROCESS;
    }

    public function isOnTraining()
    {
        return $this->status_id == self::ON_TRAINING;
    }

    public function isStatusAllowed()
    {
        return $this->status_id == self::ALLOWED;
    }

    public function isStatusTrash()
    {
        return $this->status_id == self::TRASH;
    }

    public function scopeNew($builder)
    {
        return $builder->where('status_id', self::NEW);
    }

    public function scopeInProcess($builder)
    {
        return $builder->where('status_id', self::IN_PROCESS);
    }

    public function scopeOnTraining($builder)
    {
        return $builder->where('status_id', self::ON_TRAINING);
    }


    public function scopeAllowed($builder)
    {
        return $builder->where('status_id', self::ALLOWED);
    }

    public function scopeTrash($builder)
    {
        return $builder->where('status_id', self::TRASH);
    }

    public function setStatusNew()
    {
        return $this->setStatus(self::NEW);
    }

    public function setStatusInProcess()
    {
        return $this->setStatus(self::IN_PROCESS);
    }

    public function setStatusOnTraining()
    {
        return $this->setStatus(self::ON_TRAINING);
    }

    public function setStatusAllowed()
    {
        return $this->setStatus(self::ALLOWED);
    }

    public function setStatusTrash()
    {
        return $this->setStatus(self::TRASH);
    }

    public function setStatus(int $status_id)
    {
        $status = self::getOneById($status_id);
        $this->assertStateSwitchTo($status_id);
        $this->status_updated_at = now();
        $this->status_id = $status_id;
    }


}
