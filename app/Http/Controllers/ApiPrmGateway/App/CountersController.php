<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiPrmGateway\App;

use App\Http\Controllers\Controller;
use App\Models\MerchantRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CountersController extends Controller
{
    public function merchantRequests(): JsonResponse
    {
        $count = Cache::remember('prm_merchant_requests', 60, function () {
            return MerchantRequest::query()->new()
                ->count();
        });

        return new JsonResponse(compact('count'));
    }
}
