<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Exceptions\BusinessException;
use App\Filters\AzoMerchantAccess\QAzoMerchantAccessFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\UserIdsFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUserRequest;
use App\Models\AzoMerchantAccess;
use App\UseCases\MerchantUsers\DestroyMerchantUserUseCase;
use App\UseCases\MerchantUsers\StoreMerchantUserUseCase;
use App\UseCases\MerchantUsers\UpdateMerchantUserUseCase;
use Illuminate\Http\Request;

class AzoMerchantAccessesController extends Controller
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
        $azo_merchant_access = AzoMerchantAccess::query()->with(['merchant', 'store'])->find($id);
        if ($azo_merchant_access === null) {
            throw new BusinessException('Доступ к юзеру не найден', 'not_found', 404);
        }

        return $azo_merchant_access;
    }

    public function store(StoreMerchantUsers $request, StoreMerchantUserUseCase $storeMerchantUserUseCase)
    {
        return $storeMerchantUserUseCase->execute($request->input('store_id'), $request->input('user_id'));
    }

    public function update($id, UpdateMerchantUserRequest $request, UpdateMerchantUserUseCase $updateMerchantUserUseCase)
    {
        return $updateMerchantUserUseCase->execute((int) $id, $request->input('store_id'));
    }

    public function destroy($id, DestroyMerchantUserUseCase $destroyMerchantUserUseCase)
    {
        $destroyMerchantUserUseCase->execute((int) $id);

        return response()->json(['message' => 'Сотрудник удален']);
    }
}
