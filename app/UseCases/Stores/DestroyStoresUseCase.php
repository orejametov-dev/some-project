<?php

namespace App\UseCases\Stores;

use App\UseCases\Cache\FlushCacheUseCase;
use Illuminate\Support\Facades\DB;

class DestroyStoresUseCase
{
    public function __construct(
        private FindStoresUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    )
    {
    }

    public function execute(int $id)
    {
        $store = $this->findStoresUseCase->execute($id);

        DB::transaction(function () use ($store) {
            $store->application_conditions()->delete();
            $store->delete();
        });

        $this->flushCacheUseCase->execute($store->merchant_id);

        return response()->json(['message' => 'Успешно удалено']);
    }
}
