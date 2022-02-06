<?php

namespace App\UseCases\Stores;

use App\DTOs\Stores\StoreStoresDTO;
use App\Modules\Merchants\Models\Store;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantUseCase;

class StoreStoresUseCase
{
    public function __construct(
        private FindMerchantUseCase $findMerchantUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    ) {
    }

    public function execute(StoreStoresDTO $storeStoresDTO)
    {
        $merchant = $this->findMerchantUseCase->execute($storeStoresDTO->merchant_id);

        $store_exists = Store::query()
            ->where('name', $storeStoresDTO->name)
            ->exists();

        if ($store_exists) {
            return response()->json(['message' => 'Указанное имя уже занято другим магазином'], 400);
        }

        $merchant_store = new Store();
        $merchant_store->name = $storeStoresDTO->name;
        $merchant_store->merchant_id = $merchant->id;
        $merchant_store->responsible_person = $storeStoresDTO->responsible_person;
        $merchant_store->responsible_person_phone = $storeStoresDTO->responsible_person_phone;
        $merchant_store->address = $storeStoresDTO->address;
        $merchant_store->region = $storeStoresDTO->region;
        $merchant_store->district = $storeStoresDTO->district;

        if (Store::where('merchant_id', $merchant->id)->count() === 0) {
            $merchant_store->is_main = true;
        }

        if ($storeStoresDTO->responsible_person === null) {
            $main_store = Store::query()
                ->where('merchant_id', $merchant->id)
                ->main()
                ->first();

            if ($main_store !== null) {
                $merchant_store->responsible_person = $main_store->responsible_person;
                $merchant_store->responsible_person_phone = $main_store->responsible_person_phone;
            }
        }

        $merchant_store->save();

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant_store;
    }
}
