<?php

namespace App\UseCases\Cache;

use Illuminate\Cache\Repository as CacheRepository;

class FlushCacheUseCase
{
    public function __construct(
        private CacheRepository $cacheRepository,
    ) {
    }

    public function execute(int $merchant_id) : void
    {
        $this->cacheRepository->tags($merchant_id)->flush();
        $this->cacheRepository->tags('azo_merchants')->flush();
        $this->cacheRepository->tags('company')->flush();
    }
}
