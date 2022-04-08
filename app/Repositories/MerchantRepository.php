<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Merchant;

class MerchantRepository extends AbstractRepository
{
    protected function getModelClass(): string
    {
        return Merchant::class;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @param int $company_id
     * @return mixed
     */
    public function existsByCompanyId(int $company_id): mixed
    {
        return $this->startConditions()->where('company_id', $company_id)->exists();
    }

    /**
     * @param Merchant $merchant
     * @return void
     */
    public function save(Merchant $merchant): void
    {
        $merchant->save();
    }

//    /**
//     * @param Request $request
//     * @param array $relations
//     * @param array $filter
//     * @return mixed
//     */
//    public function index(Request $request, array $relations = [], array $filter = [], $all = false): mixed
//    {
//        $merchants = $this
//            ->startConditions()
//            ->with($relations)
//            ->filter($request, $filter)
//            ->orderRequest($request)
//            ->paginate($request->query('per_page') ?? 15);
//    }
//
//    public function filter(Builder $builder, Request $request, $filters = []): Builder
//    {
//        return (new MerchantFilters($request, $builder))->execute($filters);
//    }
}
