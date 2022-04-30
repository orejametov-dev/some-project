<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MerchantRepository
{
//    private Merchant|Builder $merchant;
//
//    public function __construct()
//    {
//        $this->merchant = Merchant::query();
//    }

    /**
     * @param int $id
     * @return Merchant|Collection|null
     */
    public function findById(int $id): Merchant|Collection|null
    {
        return Merchant::query()->find($id);
    }

    /**
     * @param array $merchant_ids
     * @return Merchant[]|Collection
     */
    public function getByIds(array $merchant_ids): Merchant|Collection
    {
        return Merchant::query()->whereIn('id', $merchant_ids)
            ->get();
    }

    /**
     * @param int $company_id
     * @return bool
     */
    public function existsByCompanyId(int $company_id): bool
    {
        return Merchant::query()->where('company_id', $company_id)->exists();
    }

    /**
     * @param Merchant $merchant
     * @return void
     */
    public function save(Merchant $merchant): void
    {
        $merchant->save();
    }

    /**
     * @param Merchant $merchant
     * @param Collection $tags
     * @return void
     */
    public function syncTags(Merchant $merchant, Collection $tags): void
    {
        $merchant->tags()->sync($tags);
    }

    /**
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function checkToNameExistsByIgnoringId(int $id, string $name): bool
    {
        return Merchant::query()->where('name', $name)
            ->where('id', '!=', $id)->exists();
    }

    /**
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function checkToTokenExistsByIgnoringId(int $id, string $token): bool
    {
        return Merchant::query()->where('token', $token)
            ->where('id', '!=', $id)->exists();
    }

    /**
     * @param int $company_id
     * @return Merchant|Model|null
     */
    public function getMerchantByCompanyId(int $company_id): Merchant|Model|null
    {
        return Merchant::query()->where('company_id', $company_id)->first(['id', 'name']);
    }
}
