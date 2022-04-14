<?php

declare(strict_types=1);

namespace App\Repositories;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MerchantRepository
{
    private Merchant|Builder $merchant;

    public function __construct()
    {
        $this->merchant = Merchant::query();
    }

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
        return $this->merchant->find($id);
    }

    /**
     * @param int $company_id
     * @return bool
     */
    public function existsByCompanyId(int $company_id): bool
    {
        return $this->merchant->where('company_id', $company_id)->exists();
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
     * @param Merchant $merchant
     * @param int $activity_reason_id
     * @param GatewayAuthUser $gatewayAuthUser
     * @return void
     */
    public function attachActivityReason(Merchant $merchant, int $activity_reason_id, GatewayAuthUser $gatewayAuthUser): void
    {
        $merchant->activity_reasons()->attach($activity_reason_id, [
            'active' => $merchant->active,
            'created_by_id' => $gatewayAuthUser->getId(),
            'created_by_name' => $gatewayAuthUser->getName(),
        ]);
    }

    /**
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function checkToNameExistsByIgnoringId(int $id, string $name): bool
    {
        return $this->merchant->where('name', $name)
            ->where('id', '!=', $id)->exists();
    }

    /**
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function checkToTokenExistsByIgnoringId(int $id, string $token): bool
    {
        return $this->merchant->where('token', $token)
            ->where('id', '!=', $id)->exists();
    }

    /**
     * @param int $company_id
     * @return Merchant|Model|null
     */
    public function getMerchantByCompanyId(int $company_id): Merchant|Model|null
    {
        return $this->merchant->where('company_id', $company_id)->first(['id', 'name']);
    }
}
