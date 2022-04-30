<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ApplicationConditionRepository
{
//    private Condition|Builder $condition;
//
//    public function __construct()
//    {
//        $this->condition = Condition::query();
//    }

    /**
     * @param Condition $condition
     * @return void
     */
    public function delete(Condition $condition): void
    {
        $condition->delete();
    }

    /**
     * @param Condition $condition
     * @return void
     */
    public function save(Condition $condition): void
    {
        $condition->save();
    }

    /**
     * @param int $condition_id
     * @return Condition|Collection|null
     */
    public function getById(int $condition_id): Condition|Collection|null
    {
        return Condition::query()->find($condition_id);
    }

    /**
     * @param int $merchant_id
     * @return Condition[]|Collection
     */
    public function getByActiveTruePostAlifshopTrueWithMerchantId(int $merchant_id): Condition|Collection
    {
        return Condition::query()
            ->where('merchant_id', $merchant_id)
            ->where('active', '=', true)
            ->where('post_alifshop', '=', true)
            ->get();
    }

    /**
     * @param int $merchant_id
     * @param int $duration
     * @param int $commission
     * @return bool
     */
    public function getByDurationAndCommissionWithMerchantIdExists(int $merchant_id, int $duration, int $commission): bool
    {
        return Condition::query()->where('merchant_id', $merchant_id)
            ->where('duration', $duration)
            ->where('commission', $commission)
            ->exists();
    }
}
