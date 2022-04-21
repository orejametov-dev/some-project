<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiPrmGateway\Merchants;

use App\DTOs\Conditions\MassSpecialStoreConditionDTO;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\DTOs\Conditions\StoreConditionDTO;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrmGateway\Applications\MassSpecialStoreApplicationConditionRequest;
use App\Http\Requests\ApiPrmGateway\Applications\MassStoreApplicationConditionsRequest;
use App\Http\Requests\ApiPrmGateway\Applications\StoreApplicationConditionRequest;
use App\Http\Requests\ApiPrmGateway\Applications\TogglePostsApplicationConditionRequest;
use App\Http\Requests\ApiPrmGateway\Applications\UpdateApplicationConditionRequest;
use App\Http\Resources\ApiGateway\ApplicationConditions\ApplicationConditionResource;
use App\Http\Resources\ApiGateway\ApplicationConditions\IndexApplicationConditionResource;
use App\Models\Condition;
use App\UseCases\ApplicationConditions\DeleteApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\ToggleActiveApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\TogglePostsApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\UpdateApplicationConditionUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationConditionsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $conditionQuery = Condition::query()
            ->with('stores')
            ->filterRequest($request, [MerchantIdFilter::class])
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return new IndexApplicationConditionResource($conditionQuery->first());
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
            return IndexApplicationConditionResource::collection($conditionQuery->get());
        }

        return IndexApplicationConditionResource::collection($conditionQuery->paginate($request->query('per_page') ?? 15));
    }

    public function store(StoreApplicationConditionRequest $request, StoreApplicationConditionUseCase $storeApplicationConditionUseCase): ApplicationConditionResource
    {
        $condition = $storeApplicationConditionUseCase->execute(StoreConditionDTO::fromArray($request->validated()));
        $condition->load('stores');

        return new ApplicationConditionResource($condition);
    }

    public function massStore(MassStoreApplicationConditionsRequest $request, MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase): JsonResponse
    {
        $massStoreApplicationConditionUseCase->execute(MassStoreConditionDTO::fromArray($request->validated()));

        return new JsonResponse(['message' => 'Условия изменены']);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request, MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase): JsonResponse
    {
        $massSpecialStoreApplicationConditionUseCase->execute(MassSpecialStoreConditionDTO::fromArray($request->validated()));

        return new JsonResponse(['message' => 'Условия изменены']);
    }

    public function update(int $id, UpdateApplicationConditionRequest $request, UpdateApplicationConditionUseCase $updateApplicationConditionUseCase): ApplicationConditionResource
    {
        $condition = $updateApplicationConditionUseCase->execute($id, UpdateConditionDTO::fromArray($request->validated()));

        return new ApplicationConditionResource($condition);
    }

    public function delete(int $id, DeleteApplicationConditionUseCase $deleteApplicationConditionUseCase): JsonResponse
    {
        $deleteApplicationConditionUseCase->execute($id);

        return new JsonResponse(['message' => 'Условие удалено']);
    }

    public function toggle(int $id, ToggleActiveApplicationConditionUseCase $toggleActiveApplicationConditionUseCase): ApplicationConditionResource
    {
        $condition = $toggleActiveApplicationConditionUseCase->execute($id);

        return new ApplicationConditionResource($condition);
    }

    public function togglePosts(int $id, TogglePostsApplicationConditionRequest $request, TogglePostsApplicationConditionUseCase $togglePostsApplicationConditionUseCase): ApplicationConditionResource
    {
        $condition = $togglePostsApplicationConditionUseCase->execute($id, (bool) $request->input('post_merchant'), (bool) $request->input('post_alifshop'));

        return new ApplicationConditionResource($condition);
    }
}
