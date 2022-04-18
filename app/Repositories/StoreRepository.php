<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
    private Store|Builder $store;

    public function __construct()
    {
        $this->store = Store::query();
    }

    public function store(Store $store): void
    {
        $store->save();
    }

    /**
     * @param int $merchant_id
     * @param int $store_id
     * @return Store|Collection|null
     */
    public function getByIdWihMerchantId(int $merchant_id, int $store_id): Store|Collection|null
    {
        return $this->store->where('merchant_id', $merchant_id)->find($store_id);
    }

    /**
     * @param int $merchant_id
     * @return Store|null
     */
    public function getByIdWithMerchantIdIsMain(int $merchant_id): Store|null
    {
        /** @var Store $store */
        $store = $this->store
            ->where('merchant_id', $merchant_id)
            ->where('is_main', true)
            ->first();

        return $store;
    }

    /**
     * @param int $merchant_id
     * @param int $store_id
     * @return int|bool
     */
    public function updateIsMainToFalseAnotherStore(int $merchant_id, int $store_id): int|bool
    {
        return $this->store->where('merchant_id', $merchant_id)->where('id', '<>', $store_id)->update([
            'is_main' => false,
        ]);
    }
}
