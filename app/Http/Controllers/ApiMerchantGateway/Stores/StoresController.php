<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Stores;

use App\DTOs\Auth\AzoAccessDto;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class StoresController extends Controller
{
    public function index(Request $request, AzoAccessDto $azoAccessDto): JsonResource
    {
        $stores = Store::query()
            ->with(['merchant'])
            ->byMerchant($azoAccessDto->getMerchantId());

        return Cache::remember($request->fullUrl() . '_' . $azoAccessDto->getMerchantId(), 5 * 60, function () use ($stores, $request) {
            return $stores->paginate($request->query('per_page') ?? 15);
        });
    }
}
