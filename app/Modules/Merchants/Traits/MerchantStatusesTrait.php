<?php


namespace App\Modules\Merchants\Traits;


use App\Modules\Merchants\Services\MerchantStatus;

trait MerchantStatusesTrait
{

    public function setStatusActive()
    {
        return $this->setStatus(MerchantStatus::ACTIVE);
    }

    public function setStatusArchive()
    {
        return $this->setStatus(MerchantStatus::ARCHIVE);
    }

    public function setStatusBlock()
    {
        return $this->setStatus(MerchantStatus::BLOCK);
    }

    public function setStatus(int $status_id)
    {
        $status = MerchantStatus::getOneById($status_id);

        $this->status_updated_at = now();
        $this->status_key = $status->key;
        $this->status_id = $status->id;
    }
}
