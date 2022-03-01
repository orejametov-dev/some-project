<?php

namespace App\Modules\Merchants\QueryBuilders;

use App\Filters\Store\StoreFilters;
use App\Modules\Merchants\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StoreQueryBuilder extends Builder
{
    /**
     * @param $id
     * @param array $columns
     * @return StoreQueryBuilder|StoreQueryBuilder[]|Collection|Store|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    public function main():self
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

    public function filterRequest(Request $request, array $filters = [])
    {
        return (new StoreFilters($request, $this))->execute($filters);
    }
}
