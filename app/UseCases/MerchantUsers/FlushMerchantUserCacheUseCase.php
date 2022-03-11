<?php

declare(strict_types=1);

namespace App\UseCases\MerchantUsers;

use Illuminate\Cache\Repository as CacheRepository;

class FlushMerchantUserCacheUseCase
{
    public function __construct(
        private CacheRepository $cacheRepository,
    ) {
    }

    public function execute(int $user_id, int $merchant_id, int $authUser_id): void
    {
        $this->cacheRepository->tags('azo_merchants')->forget('azo_merchant_user_id_' . $user_id);
        $this->cacheRepository->tags('azo_merchants')->forget('active_merchant_by_user_id_' . $authUser_id);
        $this->cacheRepository->tags($merchant_id)->flush();
    }
}
