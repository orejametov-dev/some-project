<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SpecialStoreCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SpecialStoreConditionRepository
{
    private SpecialStoreCondition|Builder $specialStoreCondition;

    public function __construct()
    {
        $this->specialStoreCondition = SpecialStoreCondition::query();
    }

    /**
     * @param SpecialStoreCondition $specialStoreCondition
     * @return void
     */
    public function save(SpecialStoreCondition $specialStoreCondition): void
    {
        $specialStoreCondition->save();
    }

    /**
     * @param SpecialStoreCondition|Collection $specialStoreCondition
     * @return void
     */
    public function delete(SpecialStoreCondition|Collection $specialStoreCondition): void
    {
        $specialStoreCondition->delete();
    }

    /**
     * @param int $condition_id
     * @return Collection|SpecialStoreCondition|null
     */
    public function getByConditionId(int $condition_id): Collection|SpecialStoreCondition|null
    {
        return $this->specialStoreCondition
            ->where('condition_id', $condition_id)
            ->get();
    }
}
