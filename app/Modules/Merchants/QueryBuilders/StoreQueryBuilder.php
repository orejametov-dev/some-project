<?php

namespace App\Modules\Merchants\QueryBuilders;

use App\Filters\Store\StoreFilters;
use App\Modules\Merchants\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StoreQueryBuilder extends Builder
{
    public function main(): self
    {
        return $this->where('is_main', true);
    }

    public function byMerchant($merchant_id): self
    {
        return $this->where('merchant_id', $merchant_id);
    }

    public function active(): self
    {
        return $this->where('active', true);
    }

    public function filterRequest(Request $request, array $filters = []): Builder
    {
        return (new StoreFilters($request, $this))->execute($filters);
    }

    /**
     * @param array $columns
     * @return StoreQueryBuilder|Store|object|null
     */
    public function first($columns = ['*'])
    {
        return parent::first();
    }

    /**
     * @param $id
     * @param array $columns
     * @return StoreQueryBuilder|StoreQueryBuilder[]|Collection|Store|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    /**
     * @param array $columns
     * @return Store[]|Collection
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return parent::exists();
    }

    public function count($columns = '*')
    {
        return parent::count($columns);
    }
}
