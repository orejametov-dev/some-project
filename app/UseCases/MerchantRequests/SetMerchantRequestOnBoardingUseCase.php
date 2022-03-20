<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\Enums\MerchantRequestStatusEnum;
use App\Exceptions\BusinessException;
use App\Models\MerchantRequest;

class SetMerchantRequestOnBoardingUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase
    ) {
    }

    public function execute(int $id): MerchantRequest
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        if (($merchant_request->main_completed == true && $merchant_request->documents_completed == true && $merchant_request->file_completed == true) === false) {
            throw new BusinessException('Не все данные были заполнены для одобрения', 'data_not_completed', 400);
        }

        $merchant_request->setStatus(MerchantRequestStatusEnum::ON_TRAINING());
        $merchant_request->save();

        return $merchant_request;
    }
}
