<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Conditions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Conditions\ConditionsResource;
use App\Models\Condition;
use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

class ConditionsController extends Controller
{
    public function getConditionByMerchantId(int $merchant_id, int $condition_id): ConditionsResource
    {
        $condition = Condition::active()
            ->byMerchant($merchant_id)->findOrFail($condition_id);

        return new ConditionsResource($condition);
    }

    public function getAlifshopConditionsByCompanyId(int $company_id): JsonResource
    {
        $merchant = Merchant::query()->where('company_id', $company_id)->firstOrFail();

        return ConditionsResource::collection($merchant->application_conditions()
            ->where('post_alifshop', true)->get());
    }
}
