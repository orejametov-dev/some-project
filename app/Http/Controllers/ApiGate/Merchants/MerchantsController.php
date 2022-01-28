<?php


namespace App\Http\Controllers\ApiGate\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Merchants\MerchantDetailForCredits;
use App\Http\Resources\ApiGate\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends Controller
{
    public function getMerchantByTinForCredits($tin)
    {
        $merchant = Merchant::with('merchant_info')
            ->whereHas('merchant_info', function ($query) use ($tin) {
                $query->where('tin', $tin)->orderByDesc('contract_date');
            })->firstOrFail();

        return new MerchantDetailForCredits($merchant);
    }

    public function show($id)
    {
        $merchant_query = Merchant::with(['application_active_conditions']);
        if (preg_match('/^\d+$/', $id)) {
            $merchant = $merchant_query->findOrFail($id);
        } else {
            $merchant = $merchant_query->where('token', $id)->firstOrFail();
        }
        $merchant->main_store = Store::where('merchant_id', $merchant->id)->where('is_main', true)->first();
        return new MerchantsResource($merchant);
    }

    public function verifyToken(Request $request)
    {
        $merchant = Merchant::query()->where('token', $request->token)->firstOrFail();

        return [
            'name' => $merchant->name,
            'merchant_id' => $merchant->id
        ];
    }

    public function getMerchantByCompanyId($companyId)
    {
        $merchant = Merchant::query()->where('company_id', $companyId)->first(['id', 'name']);

        if($merchant === null) {
            throw new BusinessException('Мерчант не найден', 'object_not_found', 404);
        }

        return $merchant;
    }

}
