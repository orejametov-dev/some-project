<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Merchants\MerchantDetailForCredits;
use App\Http\Resources\ApiGate\Merchants\MerchantsResource;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsController extends Controller
{
    public function getMerchantByTinForCredits(string $tin): MerchantDetailForCredits
    {
        $merchant = Merchant::with('merchant_info')
            ->whereHas('merchant_info', function ($query) use ($tin) {
                $query->where('tin', $tin)->orderByDesc('contract_date');
            })->firstOrFail();

        return new MerchantDetailForCredits($merchant);
    }

    public function show(string $id): MerchantsResource
    {
        $merchant_query = Merchant::with(['application_active_conditions']);
        if (preg_match('/^\d+$/', $id)) {
            $merchant = $merchant_query->findOrFail($id);
        } else {
            $merchant = $merchant_query->where('token', $id)->firstOrFail();
        }

        return new MerchantsResource($merchant);
    }

    public function verifyToken(Request $request): array
    {
        $merchant = Merchant::query()->where('token', $request->token)->firstOrFail();

        return [
            'name' => $merchant->name,
            'merchant_id' => $merchant->id,
        ];
    }

    public function getMerchantByCompanyId(int $companyId, MerchantRepository $merchantRepository): JsonResource
    {
        $merchant = $merchantRepository->getMerchantByCompanyId($companyId);

        if ($merchant === null) {
            throw new BusinessException('Мерчант не найден', 'object_not_found', 404);
        }

        return new JsonResource($merchant);
    }
}
