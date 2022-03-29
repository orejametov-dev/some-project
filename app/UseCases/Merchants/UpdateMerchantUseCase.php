<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\BusinessException;
use App\Models\Merchant;
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
    public function execute(int $id, UpdateMerchantDTO $updateMerchantDTO): Merchant
    {
        // check unique name
        if (Merchant::query()->where('name', $updateMerchantDTO->getName())
            ->where('id', '!=', $id)->exists()) {
            throw new BusinessException('Мерчант с таким названием уже существует');
        }

        // check unique token
        if (Merchant::query()->where('token', $updateMerchantDTO->getToken())
            ->where('id', '!=', $id)->exists()) {
            throw new BusinessException('Мерчант с таким токеном уже существует');
        }

        $merchant = $this->findMerchantUseCase->execute($id);

        $merchant->name = $updateMerchantDTO->getName();
        $merchant->legal_name = $updateMerchantDTO->getLegalName();
        $merchant->legal_name_prefix = $updateMerchantDTO->getLegalNamePrefix();
        $merchant->token = $updateMerchantDTO->getToken();
        $merchant->min_application_price = $updateMerchantDTO->getMinApplicationPrice();
        $merchant->save();

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
