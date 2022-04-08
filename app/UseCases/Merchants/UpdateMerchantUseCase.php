<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateMerchantUseCase
{
    public function __construct(
        private MerchantRepository $merchantRepository,
        private FlushCacheUseCase $flushCacheUseCase,
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    /**
     * @throws BusinessException
     */
    public function execute(int $id, UpdateMerchantDTO $updateMerchantDTO): Merchant
    {
        if ($this->merchantRepository->checkToNameExistsNotThisId($id, $updateMerchantDTO->getName())) {
            throw new BusinessException('Мерчант с таким названием уже существует');
        }
        // check unique token
        if ($this->merchantRepository->checkToTokenExistsNotThisId($id, $updateMerchantDTO->getToken())) {
            throw new BusinessException('Мерчант с таким токеном уже существует');
        }

        $merchant = $this->findMerchantUseCase->execute($id);

        $merchant->name = $updateMerchantDTO->getName();
        $merchant->legal_name = $updateMerchantDTO->getLegalName();
        $merchant->legal_name_prefix = $updateMerchantDTO->getLegalNamePrefix();
        $merchant->token = $updateMerchantDTO->getToken();
        $this->merchantRepository->save($merchant);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
