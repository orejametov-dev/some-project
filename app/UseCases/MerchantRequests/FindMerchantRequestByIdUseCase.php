<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\Exceptions\BusinessException;
use App\Models\MerchantRequest;

class FindMerchantRequestByIdUseCase
{
    public function execute(int $id): MerchantRequest
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос на регистрацию мерчанта не найден', 'merchant_request_not_found', 404);
        }

        return $merchant_request;
    }
}
