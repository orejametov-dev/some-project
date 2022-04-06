<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\Exceptions\NotFoundException;
use App\Models\MerchantRequest;

class FindMerchantRequestByIdUseCase
{
    public function execute(int $id): MerchantRequest
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new NotFoundException('Запрос на регистрацию мерчанта не найден');
        }

        return $merchant_request;
    }
}
