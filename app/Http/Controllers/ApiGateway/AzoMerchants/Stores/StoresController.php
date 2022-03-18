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
use App\UseCases\Stores\SaveStoreUseCase;
use App\UseCases\Stores\SetTypeRegisterStoreUseCase;
use App\UseCases\Stores\ToggleStoreUseCase;
use App\UseCases\Stores\UpdateStoreUseCase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StoresController extends Controller
{
    public function index(Request $request): LengthAwarePaginator|Store|Collection
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

    public function show(int $store_id): Model
    {
        $store = Store::with(['merchant', 'activity_reasons'])
            ->findOrFail($store_id);

        return $store;
    }

    public function store(StoreStoresRequest $request, SaveStoreUseCase $storeStoresUseCase): Store
    {
        $storeStoresDTO = StoreStoresDTO::fromArray($request->validated());

        return $storeStoresUseCase->execute($storeStoresDTO);
    }

    public function update(int $store_id, UpdateStoresRequest $request, UpdateStoreUseCase $updateStoresUseCase): Store
    {
        $updateStoresDTO = UpdateStoresDTO::fromArray($store_id, $request->validated());

        return $updateStoresUseCase->execute($updateStoresDTO);
    }

    public function toggle(int $id, Request $request, ToggleStoreUseCase $toggleStoresUseCase): Store
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        return $toggleStoresUseCase->execute($id, (int) $request->input('activity_reason_id'));
    }

    public function setTypeRegister(int $id, Request $request, SetTypeRegisterStoreUseCase $setTypeRegisterStoresUseCase): Store
    {
        $request->validate([
            'client_type_register' => 'required|string',
        ]);

        return $setTypeRegisterStoresUseCase->execute($id, (string) $request->input('client_type_register'));
    }

    public function getConditions(int $id, Request $request): Collection
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
