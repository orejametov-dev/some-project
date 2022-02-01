<?php

namespace App\UseCases\Stores;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Store;

class FindStoresUseCase
{
    public function execute(int $store_id): ?Store
    {
        $store = Store::query()->find($store_id);

        if ($store === null) {
            throw new BusinessException('Магазин не найден', 'store_not_found', 404);
        }

        return $store;
    }
}
