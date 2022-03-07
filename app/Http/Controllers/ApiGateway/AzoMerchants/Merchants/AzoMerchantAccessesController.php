<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Filters\AzoMerchantAccess\QAzoMerchantAccessFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\UserIdsFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUserRequest;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\UseCases\MerchantUsers\DestroyMerchantUserUseCase;
use App\UseCases\MerchantUsers\StoreMerchantUserUseCase;
use App\UseCases\MerchantUsers\UpdateMerchantUserUseCase;
use Illuminate\Http\Request;

class AzoMerchantAccessesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $azo_merchant_accesses = AzoMerchantAccess::query()
            ->with(['merchant', 'store'])
            ->filterRequest($request, [
                MerchantIdFilter::class,
                QAzoMerchantAccessFilter::class,
                UserIdsFilter::class,
                StoreIdFilter::class,
                DateFilter::class,
            ])
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return $azo_merchant_accesses->first();
        }

        return $azo_merchant_accesses->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AzoMerchantAccess::with(['merchant', 'store'])->findOrFail($id);
    }

    public function store(StoreMerchantUsers $request, StoreMerchantUserUseCase $storeMerchantUserUseCase)
    {
        return $storeMerchantUserUseCase->execute($request->input('store_id'), $request->input('user_id'));
    }

    public function update($id, UpdateMerchantUserRequest $request, UpdateMerchantUserUseCase $updateMerchantUserUseCase)
    {
        return $updateMerchantUserUseCase->execute($id, $request->input('store_id'));
    }

    public function destroy($id, DestroyMerchantUserUseCase $destroyMerchantUserUseCase)
    {
        $destroyMerchantUserUseCase->execute($id);

        return response()->json(['message' => 'Сотрудник удален']);
    }
}
