<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiOnlineGateway\Conditions;

use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\ConditionsResource;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConditionsController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $conditionQuery = Condition::query()
            ->active()
            ->postMerchant()
            ->filterRequest($request, [MerchantIdFilter::class])
            ->orderRequest($request);

        return ConditionsResource::collection($conditionQuery->paginate($request->query('per_page') ?? 15));
    }
}
