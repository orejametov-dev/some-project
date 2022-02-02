<?php

namespace App\UseCases\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Modules\Merchants\Models\Complaint;

class StoreComplaintUseCase
{
    public function execute(StoreComplaintDTO $storeComplaintDTO): Complaint
    {
        $complaint = new Complaint();
        $complaint->user_id = $storeComplaintDTO->user_id;
        $complaint->name = $storeComplaintDTO->name;
        $complaint->surname = $storeComplaintDTO->surname;
        $complaint->patronymic = $storeComplaintDTO->patronymic;
        $complaint->reason_correction = $storeComplaintDTO->reason_correction;

        $complaint->save();

        return $complaint;
    }
}
