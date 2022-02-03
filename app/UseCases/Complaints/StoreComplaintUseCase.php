<?php

namespace App\UseCases\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Complaint;

class StoreComplaintUseCase
{
    public function execute(StoreComplaintDTO $storeComplaintDTO): Complaint
    {
        $merchant_access = AzoMerchantAccess::query()->find($storeComplaintDTO->user_id);

        if ($merchant_access === null) {
            throw new BusinessException('Пользователь не найден' , 'object_not_found' , 404);
        }

        $complaint = new Complaint();
        $complaint->merchant_access_id = $storeComplaintDTO->user_id;
        $complaint->reason_correction = $storeComplaintDTO->reason_correction;

        $complaint->save();

        return $complaint;
    }
}
