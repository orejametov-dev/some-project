<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\Enums\MerchantRequestStatusEnum;
use App\Exceptions\BusinessException;
use App\Models\MerchantRequest;
use App\UseCases\Auth\FindAuthUserByIdUseCase;
use Carbon\Carbon;

class SetMerchantRequestEngagedUseCase
{
    public function __construct(
        private FindAuthUserByIdUseCase $findAuthUserByIdUseCase,
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase
    ) {
    }

    public function execute(int $id, int $engaged_by_id): MerchantRequest
    {
        $user = $this->findAuthUserByIdUseCase->execute($engaged_by_id);

        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        if ($merchant_request->isStatusNew() === false || $merchant_request->isInProcess() === false) {
            throw new BusinessException('Не валидный статус для указания отвественного');
        }

        $merchant_request->engaged_by_id = $user->id;
        $merchant_request->engaged_by_name = $user->name;
        $merchant_request->engaged_at = Carbon::now();
        $merchant_request->setStatus(MerchantRequestStatusEnum::IN_PROCESS());
        $merchant_request->save();

        return $merchant_request;
    }
}
