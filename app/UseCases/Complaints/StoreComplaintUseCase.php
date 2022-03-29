<?php

declare(strict_types=1);

namespace App\UseCases\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Exceptions\BusinessException;
use App\Models\AzoMerchantAccess;
use App\Models\Complaint;

class StoreComplaintUseCase
{
    public function execute(StoreComplaintDTO $storeComplaintDTO): Complaint
    {
        $merchant_access = AzoMerchantAccess::query()
            ->withTrashed()
            ->where('user_id', $storeComplaintDTO->getUserId())
            ->first();

        if ($merchant_access === null) {
            throw new BusinessException('Сотрудник не найден', 'object_not_found', 404);
        }

        $complaint = new Complaint();
        $complaint->azo_merchant_access_id = $merchant_access->id;
        $complaint->meta = $storeComplaintDTO->getMeta()->jsonSerialize();
        $complaint->save();

        return $complaint;
    }
}
