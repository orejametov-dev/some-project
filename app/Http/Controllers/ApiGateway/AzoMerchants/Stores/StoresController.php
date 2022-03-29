<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Stores;

use App\DTOs\Stores\StoreStoresDTO;
use App\DTOs\Stores\UpdateStoresDTO;
use App\Filters\CommonFilters\ActiveFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Store\QStoreFilter;
use App\Filters\Store\RegionFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Stores\StoreStoresRequest;
use App\Http\Requests\ApiPrm\Stores\UpdateStoresRequest;
use App\Models\Condition;
use App\Models\Store;
use App\UseCases\Stores\FindStoreByIdUseCase;
use App\UseCases\Stores\SaveStoreUseCase;
use App\UseCases\Stores\SetTypeRegisterStoreUseCase;
use App\UseCases\Stores\ToggleStoreUseCase;
use App\UseCases\Stores\UpdateStoreUseCase;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::query()->with(['merchant'])
            ->filterRequest($request, [
                QStoreFilter::class,
                MerchantIdFilter::class,
                RegionFilter::class,
                ActiveFilter::class,
            ]);

        if ($request->query('object') == 'true') {
            return $stores->first();
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                or $request->query('paginate') === '0')) {
            return $stores->get();
        }

        return $stores->paginate($request->query('per_page') ?? 15);
    }

    public function show($id, FindStoreByIdUseCase $findStoreByIdUseCase)
    {
        $store = $findStoreByIdUseCase->execute((int) $id);
        $store->load(['merchant', 'activity_reasons']);

        return $store;
    }

    public function store(StoreStoresRequest $request, SaveStoreUseCase $storeStoresUseCase)
    {
        return $storeStoresUseCase->execute(StoreStoresDTO::fromArray($request->validated()));
    }

    public function update($id, UpdateStoresRequest $request, UpdateStoreUseCase $updateStoresUseCase)
    {
        return $updateStoresUseCase->execute((int) $id, UpdateStoresDTO::fromArray($request->validated()));
    }

    public function toggle($id, Request $request, ToggleStoreUseCase $toggleStoresUseCase)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        return $toggleStoresUseCase->execute((int) $id, (int) $request->input('activity_reason_id'));
    }

    public function setTypeRegister($id, Request $request, SetTypeRegisterStoreUseCase $setTypeRegisterStoresUseCase)
    {
        $request->validate([
            'client_type_register' => 'required|string',
        ]);

        return $setTypeRegisterStoresUseCase->execute((int) $id, (string) $request->input('client_type_register'));
    }

    public function getConditions($id, Request $request)
    {
        $store = Store::query()->findOrFail($id);
        $special_conditions = $store->conditions()->active()->get();

        $conditionQuery = Condition::query()
            ->active()
            ->where('is_special', false)
            ->byMerchant($store->merchant_id)
            ->filterRequest($request, [])
            ->orderRequest($request)->get();

        return $conditionQuery->merge($special_conditions)->sortByDesc('updated_at');
    }
}
