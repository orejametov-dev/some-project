<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Filters\AzoMerchantAccess\QAzoMerchantAccessFilter;
use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\UserIdsFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUserRequest;
use App\Http\Resources\ApiGateway\AzoMerchantAccesses\AzoMerchantAccessResource;
use App\Models\AzoMerchantAccess;
use App\UseCases\MerchantUsers\DestroyMerchantUserUseCase;
use App\UseCases\MerchantUsers\FindMerchantUserByIdUseCase;
use App\UseCases\MerchantUsers\StoreMerchantUserUseCase;
use App\UseCases\MerchantUsers\UpdateMerchantUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AzoMerchantAccessesController extends Controller
{
    public function index(Request $request): JsonResource
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
            return new AzoMerchantAccessResource($azo_merchant_accesses->first());
        }

        return AzoMerchantAccessResource::collection($azo_merchant_accesses->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindMerchantUserByIdUseCase $findMerchantUserByIdUseCase): AzoMerchantAccessResource
    {
        $azo_merchant_access = $findMerchantUserByIdUseCase->execute($id);

        return new AzoMerchantAccessResource($azo_merchant_access->load(['merchant', 'store']));
    }

    public function store(StoreMerchantUsers $request, StoreMerchantUserUseCase $storeMerchantUserUseCase): AzoMerchantAccessResource
    {
        $azo_merchant_access = $storeMerchantUserUseCase->execute($request->input('store_id'), $request->input('user_id'));

        return new AzoMerchantAccessResource($azo_merchant_access);
    }

    public function update(int $id, UpdateMerchantUserRequest $request, UpdateMerchantUserUseCase $updateMerchantUserUseCase): AzoMerchantAccessResource
    {
        $azo_merchant_access = $updateMerchantUserUseCase->execute($id, $request->input('store_id'));

        return new AzoMerchantAccessResource($azo_merchant_access);
    }

    public function destroy(int $id, DestroyMerchantUserUseCase $destroyMerchantUserUseCase): JsonResponse
    {
        $destroyMerchantUserUseCase->execute($id);

        return new JsonResponse(['message' => 'Сотрудник удален']);
    }
}
