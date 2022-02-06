<?php

namespace App\Http\Controllers\ApiOnlineGateway\Conditions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\ConditionsResource;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Request;

class ConditionsController extends Controller
{
    public function index(Request $request)
    {
        $conditionQuery = Condition::query()
            ->active()
            ->postMerchant()
            ->filterRequest($request)
            ->orderRequest($request);

        return ConditionsResource::collection($conditionQuery->paginate($request->query('per_page') ?? 15));
    }
}
