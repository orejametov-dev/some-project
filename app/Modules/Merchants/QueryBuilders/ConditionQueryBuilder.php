<?php
//
//namespace App\Modules\Merchants\QueryBuilders;
//
//use App\Filters\Condition\ConditionFilters;
//use App\Modules\Merchants\Models\Condition;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Http\Request;
//
//class ConditionQueryBuilder extends Builder
//{
//    public function active(): self
//    {
//        return $this->where('active', true);
//    }
//
//    public function postMerchant(): self
//    {
//        return $this->where('post_merchant', true);
//    }
//
//    public function byMerchant($merchant_id): self
//    {
//        return $this->where('merchant_id', $merchant_id);
//    }
//
//    public function filterRequest(Request $request, array $filters = []): Builder
//    {
//        return (new ConditionFilters($request, $this))->execute($filters);
//    }
//
//    /**
//     * @param array $columns
//     * @return ConditionQueryBuilder|Condition|object|null
//     */
//    public function first($columns = ['*'])
//    {
//        return parent::first();
//    }
//
//    /**
//     * @param $id
//     * @param array $columns
//     * @return ConditionQueryBuilder|ConditionQueryBuilder[]|Collection|Condition|null
//     */
//    public function find($id, $columns = ['*'])
//    {
//        return parent::find($id, $columns);
//    }
//
//    /**
//     * @param array $columns
//     * @return Condition[]|Collection
//     */
//    public function get($columns = ['*'])
//    {
//        return parent::get($columns);
//    }
//
//    public function exists()
//    {
//        return parent::exists();
//    }
//}
