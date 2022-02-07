<?php

namespace App\UseCases\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Complaint;
use App\UseCases\MerchantUsers\FindMerchantUserUseCase;

class StoreComplaintUseCase
{
    public function __construct(
        private FindMerchantUserUseCase $findMerchantUserUseCase,
    )
    {
    }

    public function execute(StoreComplaintDTO $storeComplaintDTO): Complaint
    {
        $merchant_access = $this->findMerchantUserUseCase->execute($storeComplaintDTO->user_id);

        $complaint = new Complaint();
        $complaint->azo_merchant_access_id = $merchant_access->id;
        $complaint->meta = $storeComplaintDTO->meta;
        $complaint->reason_correction = $storeComplaintDTO->reason_correction;

        $complaint->save();

        return $complaint;
    }
}
