<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\Enums\MerchantRequestStatusEnum;
use App\Exceptions\BusinessException;
use App\Models\CancelReason;
use App\Models\MerchantRequest;

class RejectMerchantRequestUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase,
    ) {
    }

    public function execute(int $id, int $cancel_reason_id): MerchantRequest
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        $cancel_reason = CancelReason::query()->find($cancel_reason_id);
        if ($cancel_reason === null) {
            throw new BusinessException('Причина отказа не найдена');
        }

        if ($merchant_request->isInProcess() === false && $merchant_request->isOnTraining() === false) {
            throw new BusinessException('Статус заявки должен быть "На переговорах" или "На обучение"');
        }

        $merchant_request->setStatus(MerchantRequestStatusEnum::TRASH());
        $merchant_request->cancel_reason_id = $cancel_reason->id;
        $merchant_request->save();

        return $merchant_request;
    }
}
