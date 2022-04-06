<?php

namespace App\UseCases\Stores;

use App\Exceptions\NotFoundException;
use App\Models\Store;

class FindStoreByIdUseCase
{
    public function execute(int $id): Store
    {
        $store = Store::query()->find($id);

        if ($store === null) {
            throw new NotFoundException('Магазин не найден');
        }

        return $store;
    }
}
