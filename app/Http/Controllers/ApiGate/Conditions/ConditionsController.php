<?php


namespace App\Http\Controllers\ApiGate\Conditions;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Conditions\ConditionsResource;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use Faker\Provider\Company;
use Illuminate\Http\Request;

class ConditionsController extends Controller
{
    public function getConditionByMerchantId($merchant_id, $condition_id)
    {
        $condition = Condition::active()
            ->byMerchant($merchant_id)->findOrFail($condition_id);

        return new ConditionsResource($condition);
    }

    public function getAlifshopConditionsByCompanyId($company_id)
    {
        $merchant = Merchant::query()->where('company_id', $company_id)->firstOrFail();
        return ConditionsResource::collection($merchant->application_conditions()
            ->where('post_alifshop', true)->get());
    }
}
