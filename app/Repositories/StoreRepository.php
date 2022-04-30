<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
//    private Store|Builder $store;
//
//    public function __construct()
//    {
//        Store::query( = Store::query();
//    }

    public function save(Store $store): void
    {
        $store->save();
    }

    /**
     * @param int $id
     * @return Store|Collection|null
     */
    public function getById(int $id): Store|Collection|null
    {
        return Store::query()->find($id);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkToNameExists(string $name): bool
    {
        return Store::query()->where('name', $name)->exists();
    }

    /**
     * @param int $merchant_id
     * @return int
     */
    public function checkForTheCountByMerchantId(int $merchant_id): int
    {
        return Store::query()->where('merchant_id', $merchant_id)->count();
    }

    /**
     * @param int $merchant_id
     * @param int $store_id
     * @return Store|Collection|null
     */
    public function getByIdWihMerchantId(int $merchant_id, int $store_id): Store|Collection|null
    {
        return Store::query()->where('merchant_id', $merchant_id)->find($store_id);
    }

    /**
     * @param int $merchant_id
     * @return Store|null
     */
    public function getByIsMainTrueMerchantId(int $merchant_id): Store|null
    {
        /** @var Store $store */
        $store = Store::query()
            ->where('merchant_id', $merchant_id)
            ->where('is_main', true)
            ->first();

        return $store;
    }

    /**
     * @param int $merchant_id
     * @return Store[]|Collection
     */
    public function getByActiveTrueMerchantId(int $merchant_id): Store|Collection
    {
        return Store::query()
            ->where('merchant_id', $merchant_id)
            ->where('active', true)
            ->get();
    }

    /**
     * @param int $merchant_id
     * @param int $store_id
     * @return int|bool
     */
    public function setMainForSpecificStoreByIgnoringOtherStores(int $merchant_id, int $store_id): int|bool
    {
        return Store::query()->where('merchant_id', $merchant_id)->where('id', '<>', $store_id)->update([
            'is_main' => false,
        ]);
    }
}
