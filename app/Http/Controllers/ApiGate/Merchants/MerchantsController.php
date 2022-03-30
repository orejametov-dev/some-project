<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Merchants\MerchantDetailForCredits;
use App\Http\Resources\ApiGate\Merchants\MerchantsResource;
use App\Models\Merchant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsController extends Controller
{
    public function getMerchantByTinForCredits($tin): MerchantDetailForCredits
    {
        $merchant = Merchant::with('merchant_info')
            ->whereHas('merchant_info', function ($query) use ($tin) {
                $query->where('tin', $tin)->orderByDesc('contract_date');
            })->firstOrFail();

        return new MerchantDetailForCredits($merchant);
    }

    public function show($id): MerchantsResource
    {
        $merchant_query = Merchant::with(['application_active_conditions']);
        if (preg_match('/^\d+$/', $id)) {
            $merchant = $merchant_query->findOrFail($id);
        } else {
            $merchant = $merchant_query->where('token', $id)->firstOrFail();
        }

        return new MerchantsResource($merchant);
    }

    public function verifyToken(Request $request): JsonResponse
    {
        $merchant = Merchant::query()->where('token', $request->token)->firstOrFail();

        return new JsonResponse([
            'name' => $merchant->name,
            'merchant_id' => $merchant->id,
        ]);
    }

    public function getMerchantByCompanyId($companyId): JsonResource
    {
        $merchant = Merchant::query()->where('company_id', $companyId)->first(['id', 'name']);

        if ($merchant === null) {
            throw new BusinessException('Мерчант не найден', 'object_not_found', 404);
        }

        return new JsonResource($merchant);
    }
}
