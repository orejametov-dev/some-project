<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\DTOs\Auth\AzoAccessDto;
use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConditionsController extends Controller
{
    public function index(Request $request, AzoAccessDto $azoAccessDto): array
    {
        return Cache::tags($azoAccessDto->getMerchantId())->remember(
            $request->fullUrl() . $azoAccessDto->getStoreId(),
            24 * 60,
            function () use ($request, $azoAccessDto) {
                $store = Store::query()->findOrFail($azoAccessDto->getStoreId());

                if ($request->has('post_alifshop') and $request->query('post_alifshop') == true) {
                    $special_conditions = $store->conditions()
                        ->where('post_alifshop', true)
                        ->active()
                        ->get();

                    $conditionQuery = Condition::query()
                        ->active()
                        ->where('post_alifshop', true)
                        ->where('is_special', false)
                        ->byMerchant($azoAccessDto->getMerchantId())
                        ->orderRequest($request);
                } else {
                    $special_conditions = $store->conditions()
                        ->where('post_merchant', true)
                        ->active()
                        ->get();

                    $conditionQuery = Condition::query()
                        ->active()
                        ->where('post_merchant', true)
                        ->where('is_special', false)
                        ->byMerchant($azoAccessDto->getMerchantId())
                        ->orderRequest($request);
                }

                return array_merge($conditionQuery->get()->toArray(), $special_conditions->toArray());
            }
        );
    }
}
