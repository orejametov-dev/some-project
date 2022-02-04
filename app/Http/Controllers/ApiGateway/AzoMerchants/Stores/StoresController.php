<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Stores;

use App\DTOs\Stores\StoreStoresDTO;
use App\DTOs\Stores\UpdateStoresDTO;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Stores\StoreStoresRequest;
use App\Http\Requests\ApiPrm\Stores\UpdateStoresRequest;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Store;
use App\UseCases\Stores\SetTypeRegisterStoresUseCase;
use App\UseCases\Stores\StoreStoresUseCase;
use App\UseCases\Stores\ToggleStoresUseCase;
use App\UseCases\Stores\UpdateStoresUseCase;
use Illuminate\Http\Request;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $stores = Store::query()->with(['merchant'])
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $stores->first();
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                or $request->query('paginate') === '0')) {
            return $stores->get();
        }

        return $stores->paginate($request->query('per_page') ?? 15);
    }

    public function show($store_id)
    {
        $store = Store::with(['merchant', 'activity_reasons'])
            ->findOrFail($store_id);

        return $store;
    }

    public function store(StoreStoresRequest $request, StoreStoresUseCase $storeStoresUseCase)
    {
        $storeStoresDTO = StoreStoresDTO::fromArray($request->validated());

        return $storeStoresUseCase->execute($storeStoresDTO);
    }

    public function update($store_id, UpdateStoresRequest $request, UpdateStoresUseCase $updateStoresUseCase)
    {
        $updateStoresDTO = UpdateStoresDTO::fromArray((int)$store_id, $request->validated());

        return $updateStoresUseCase->execute($updateStoresDTO);
    }

    public function toggle($id, Request $request, ToggleStoresUseCase $toggleStoresUseCase)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required'
        ]);

        return $toggleStoresUseCase->execute((int)$id, (int)$request->input('activity_reason_id'));
    }

    public function setTypeRegister($id, Request $request, SetTypeRegisterStoresUseCase $setTypeRegisterStoresUseCase)
    {
        $request->validate([
            'client_type_register' => 'required|string'
        ]);

        return $setTypeRegisterStoresUseCase->execute((int)$id, (string)$request->input('client_type_register'));
    }

    public function getConditions($id, Request $request)
    {
        $store = Store::findOrFail($id);
        $special_conditions = $store->conditions()->active()->get();

        $conditionQuery = Condition::query()
            ->active()
            ->where('is_special', false)
            ->byMerchant($store->merchant_id)
            ->filterRequest($request)
            ->orderRequest($request)->get();

        return $conditionQuery->merge($special_conditions)->sortByDesc('updated_at');
    }

}
