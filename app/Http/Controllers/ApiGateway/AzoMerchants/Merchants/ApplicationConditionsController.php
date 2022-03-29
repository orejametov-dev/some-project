<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Conditions\MassSpecialStoreConditionDTO;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\DTOs\Conditions\StoreConditionDTO;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Applications\MassSpecialStoreApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\MassStoreApplicationConditionsRequest;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\TogglePostsApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditionRequest;
use App\Models\Condition;
use App\UseCases\ApplicationConditions\DeleteApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\ToggleActiveApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\TogglePostsApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\UpdateApplicationConditionUseCase;
use Illuminate\Http\Request;

class ApplicationConditionsController extends Controller
{
    public function index(Request $request)
    {
        $conditionQuery = Condition::query()
            ->with('stores')
            ->filterRequest($request, [MerchantIdFilter::class])
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return $conditionQuery->first();
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
            return $conditionQuery->get();
        }

        return $conditionQuery->paginate($request->query('per_page') ?? 15);
    }

    public function store(StoreApplicationConditionRequest $request, StoreApplicationConditionUseCase $storeApplicationConditionUseCase)
    {
        return $storeApplicationConditionUseCase->execute(StoreConditionDTO::fromArray($request->validated()));
    }

    public function massStore(MassStoreApplicationConditionsRequest $request, MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase)
    {
        $massStoreApplicationConditionUseCase->execute(MassStoreConditionDTO::fromArray($request->validated()));

        return response()->json(['message' => 'Условия изменены']);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request, MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase)
    {
        $massSpecialStoreApplicationConditionUseCase->execute(MassSpecialStoreConditionDTO::fromArray($request->validated()));

        return response()->json(['message' => 'Условия изменены']);
    }

    public function update($id, UpdateApplicationConditionRequest $request, UpdateApplicationConditionUseCase $updateApplicationConditionUseCase)
    {
        return $updateApplicationConditionUseCase->execute((int) $id, UpdateConditionDTO::fromArray($request->validated()));
    }

    public function delete($id, DeleteApplicationConditionUseCase $deleteApplicationConditionUseCase)
    {
        $deleteApplicationConditionUseCase->execute((int) $id);

        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($id, ToggleActiveApplicationConditionUseCase $toggleActiveApplicationConditionUseCase)
    {
        return $toggleActiveApplicationConditionUseCase->execute((int) $id);
    }

    public function togglePosts($id, TogglePostsApplicationConditionRequest $request, TogglePostsApplicationConditionUseCase $togglePostsApplicationConditionUseCase)
    {
        return $togglePostsApplicationConditionUseCase->execute((int) $id, (bool) $request->input('post_merchant'), (bool) $request->input('post_alifshop'));
    }
}
