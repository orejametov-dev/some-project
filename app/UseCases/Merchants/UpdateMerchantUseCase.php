<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateMerchantUseCase
{
    public function __construct(
        private FlushCacheUseCase $flushCacheUseCase,
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    /**
     * @throws BusinessException
     */
    public function execute(UpdateMerchantDTO $updateMerchantDTO): Merchant
    {
        // check unique name
        if (Merchant::query()->where('name', $updateMerchantDTO->name)
            ->where('id', '!=', $updateMerchantDTO->id)->exists()) {
            throw new BusinessException('Мерчант с таким названием уже существует');
        }

        // check unique token
        if (Merchant::query()->where('token', $updateMerchantDTO->token)
            ->where('id', '!=', $updateMerchantDTO->id)->exists()) {
            throw new BusinessException('Мерчант с таким токеном уже существует');
        }

        $merchant = $this->findMerchantUseCase->execute($updateMerchantDTO->id);

        $merchant->name = $updateMerchantDTO->name;
        $merchant->legal_name = $updateMerchantDTO->legal_name;
        $merchant->legal_name_prefix = $updateMerchantDTO->legal_name_prefix;
        $merchant->token = $updateMerchantDTO->token;
        $merchant->min_application_price = $updateMerchantDTO->min_application_price;
        $merchant->save();

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
