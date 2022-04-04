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
use App\Http\Resources\ApiGateway\ApplicationConditions\ApplicationConditionResource;
use App\Http\Resources\ApiGateway\Stores\IndexStoreResource;
use App\Http\Resources\ApiGateway\Stores\ShowStoreResource;
use App\Http\Resources\ApiGateway\Stores\StoreResource;
use App\Models\Condition;
use App\Models\Store;
use App\UseCases\Stores\FindStoreByIdUseCase;
use App\UseCases\Stores\SaveStoreUseCase;
use App\UseCases\Stores\SetTypeRegisterStoreUseCase;
use App\UseCases\Stores\ToggleStoreUseCase;
use App\UseCases\Stores\UpdateStoreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoresController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $stores = Store::query()
            ->with(['merchant'])
            ->filterRequest($request, [
                QStoreFilter::class,
                MerchantIdFilter::class,
                RegionFilter::class,
                ActiveFilter::class,
            ]);

        if ($request->query('object') == 'true') {
            return new IndexStoreResource($stores->first());
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                or $request->query('paginate') === '0')) {
            return IndexStoreResource::collection($stores->get());
        }

        return IndexStoreResource::collection($stores->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindStoreByIdUseCase $findStoreByIdUseCase): ShowStoreResource
    {
        $store = $findStoreByIdUseCase->execute($id);
        $store->load(['merchant', 'activity_reasons']);

        return new ShowStoreResource($store);
    }

    public function store(StoreStoresRequest $request, SaveStoreUseCase $storeStoresUseCase): StoreResource
    {
        $store = $storeStoresUseCase->execute(StoreStoresDTO::fromArray($request->validated()));

        return new StoreResource($store);
    }

    public function update(int $id, UpdateStoresRequest $request, UpdateStoreUseCase $updateStoresUseCase): StoreResource
    {
        $store = $updateStoresUseCase->execute($id, UpdateStoresDTO::fromArray($request->validated()));

        return new StoreResource($store);
    }

    public function toggle(int $id, Request $request, ToggleStoreUseCase $toggleStoresUseCase): StoreResource
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        $store = $toggleStoresUseCase->execute($id, (int) $request->input('activity_reason_id'));

        return new StoreResource($store);
    }

    public function setTypeRegister(int $id, Request $request, SetTypeRegisterStoreUseCase $setTypeRegisterStoresUseCase): StoreResource
    {
        $request->validate([
            'client_type_register' => 'required|string',
        ]);

        $store = $setTypeRegisterStoresUseCase->execute($id, (string) $request->input('client_type_register'));

        return new StoreResource($store);
    }

    public function getConditions(int $id, Request $request): JsonResource
    {
        $store = Store::query()->findOrFail($id);
        $special_conditions = $store->conditions()->active()->get();

        $conditionQuery = Condition::query()
            ->active()
            ->where('is_special', false)
            ->byMerchant($store->merchant_id)
            ->filterRequest($request, [])
            ->orderRequest($request)->get();

        return ApplicationConditionResource::collection($conditionQuery->merge($special_conditions)->sortByDesc('updated_at'));
    }
}
