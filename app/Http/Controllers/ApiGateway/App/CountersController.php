<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\App;

use App\Http\Controllers\Controller;
use App\Models\MerchantRequest;
use Illuminate\Support\Facades\Cache;

class CountersController extends Controller
{
    public function merchantRequests()
    {
        $count = Cache::remember('prm_merchant_requests', 60, function () {
            return MerchantRequest::new()
                ->count();
        });

        return response()->json(compact('count'));
    }
}
