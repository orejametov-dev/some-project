<?php

namespace App\Modules\Merchants\QueryBuilders;

use App\Filters\Merchant\MerchantFilters;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MerchantQueryBuilder extends Builder
{
    public function active(): self
    {
        return $this->where('active', true);
    }

    public function filterRequest(Request $request, array $filters = []): Builder
    {
        return (new MerchantFilters($request, $this))->execute($filters);
    }

    /**
     * @param array $columns
     * @return MerchantQueryBuilder|Merchant|object|null
     */
    public function first($columns = ['*'])
    {
        return parent::first();
    }

    /**
     * @param $id
     * @param array $columns
     * @return MerchantQueryBuilder|MerchantQueryBuilder[]|Collection|Merchant|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    /**
     * @param array $columns
     * @return Merchant[]|Collection
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns);
    }
}
