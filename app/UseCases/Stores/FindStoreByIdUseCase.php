<?php

namespace App\UseCases\Stores;

use App\Exceptions\NotFoundException;
use App\Models\Store;
use App\Repositories\StoreRepository;

class FindStoreByIdUseCase
{
    public function __construct(
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(int $id): Store
    {
        $store = $this->storeRepository->getById($id);

        if ($store === null) {
            throw new NotFoundException('Магазин не найден');
        }

        return $store;
    }
}
