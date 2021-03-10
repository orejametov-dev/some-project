<?php


namespace App\Modules\Merchants\Traits;


use App\Modules\Merchants\Services\RequestStatus;

trait MerchantRequestStatusesTrait
{
    public function isStatusNew()
    {
        return $this->status_id == RequestStatus::NEW;
    }

    public function isInProcess()
    {
        return $this->status_id == RequestStatus::IN_PROCESS;
    }

    public function isStatusAllowed()
    {
        return $this->status_id == RequestStatus::ALLOWED;
    }

    public function isStatusTrash()
    {
        return $this->status_id == RequestStatus::TRASH;
    }

    public function scopeNew($builder)
    {
        return $builder->where('status_id', RequestStatus::NEW);
    }

    public function scopeInProcess($builder)
    {
        return $builder->where('status_id', RequestStatus::IN_PROCESS);
    }

    public function scopeAllowed($builder)
    {
        return $builder->where('status_id', RequestStatus::ALLOWED);
    }

    public function scopeTrash($builder)
    {
        return $builder->where('status_id', RequestStatus::TRASH);
    }

    public function setStatusNew()
    {
        return $this->setStatus(RequestStatus::NEW);
    }

    public function setStatusInProcess()
    {
        return $this->setStatus(RequestStatus::IN_PROCESS);
    }

    public function setStatusAllowed()
    {
        return $this->setStatus(RequestStatus::ALLOWED);
    }

    public function setStatusTrash()
    {
        return $this->setStatus(RequestStatus::TRASH);
    }

    public function setStatus(int $status_id)
    {
        $this->status_updated_at = now();
        return $this->status_id = $status_id;
    }

}
