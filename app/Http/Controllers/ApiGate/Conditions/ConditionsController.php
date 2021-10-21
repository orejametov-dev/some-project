<?php


namespace App\Http\Controllers\ApiGate\Conditions;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Conditions\ConditionsResource;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Request;

class ConditionsController extends Controller
{
    public function getConditionByMerchantId($merchant_id, $condition_id)
    {
        $condition = Condition::active()
            ->where('post_merchant', true)
            ->byMerchant($merchant_id)->findOrFail($condition_id);

        return new ConditionsResource($condition);
    }
}
