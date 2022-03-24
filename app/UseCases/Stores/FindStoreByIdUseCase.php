<?php

namespace App\UseCases\Stores;

use App\Exceptions\BusinessException;
use App\Models\Store;

class FindStoreByIdUseCase
{
    public function execute(int $id): Store
    {
        $store = Store::query()->find($id);

        if ($store === null) {
            throw new BusinessException('Магазин не найден', 'object_not_found', 404);
        }

        return $store;
    }
}
