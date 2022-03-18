<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Conditions\MassSpecialStoreConditionDTO;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\DTOs\Conditions\StoreConditionDTO;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Applications\MassSpecialStoreApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\MassStoreApplicationConditionsRequest;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditions;
use App\Http\Requests\ApiPrm\Applications\TogglePostsApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditions;
use App\Models\Condition;
use App\UseCases\ApplicationConditions\DeleteApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\ToggleActiveApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\TogglePostsApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\UpdateApplicationConditionUseCase;
use Illuminate\Http\Request;

class ApplicationConditionsController extends ApiBaseController
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

    public function store(StoreApplicationConditions $request, StoreApplicationConditionUseCase $storeApplicationConditionUseCase)
    {
        $conditionDTO = StoreConditionDTO::fromArray($request->validated());

        return $storeApplicationConditionUseCase->execute($conditionDTO);
    }

    public function massStore(MassStoreApplicationConditionsRequest $request, MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase)
    {
        $massStoreConditionDTO = MassStoreConditionDTO::fromArray($request->validated());

        $massStoreApplicationConditionUseCase->execute($massStoreConditionDTO);

        return response()->json(['message' => 'Условия изменены']);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request, MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase)
    {
        $massSpecialStoreConditionDTO = MassSpecialStoreConditionDTO::fromArray($request->validated());

        $massSpecialStoreApplicationConditionUseCase->execute($massSpecialStoreConditionDTO);

        return response()->json(['message' => 'Условия изменены']);
    }

    public function update($condition_id, UpdateApplicationConditions $request, UpdateApplicationConditionUseCase $updateApplicationConditionUseCase)
    {
        $updateConditionDTO = UpdateConditionDTO::fromArray((int) $condition_id, $request->validated());

        return $updateApplicationConditionUseCase->execute($updateConditionDTO);
    }

    public function delete($condition_id, DeleteApplicationConditionUseCase $deleteApplicationConditionUseCase)
    {
        $deleteApplicationConditionUseCase->execute((int) $condition_id);

        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($condition_id, ToggleActiveApplicationConditionUseCase $toggleActiveApplicationConditionUseCase)
    {
        return $toggleActiveApplicationConditionUseCase->execute((int) $condition_id);
    }

    public function togglePosts($id, TogglePostsApplicationConditionRequest $request, TogglePostsApplicationConditionUseCase $togglePostsApplicationConditionUseCase)
    {
        return $togglePostsApplicationConditionUseCase->execute((int) $id, (bool) $request->input('post_merchant'), (bool) $request->input('post_alifshop'));
    }
}
