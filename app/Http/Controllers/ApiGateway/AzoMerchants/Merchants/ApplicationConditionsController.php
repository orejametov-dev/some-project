<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Conditions\MassSpecialStoreConditionDTO;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\DTOs\Conditions\StoreConditionDTO;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Applications\MassSpecialStoreApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\MassStoreApplicationConditionsRequest;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditions;
use App\Http\Requests\ApiPrm\Applications\TogglePostsApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditions;
use App\Modules\Merchants\Models\Condition;
use App\UseCases\ApplicationConditions\DeleteApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\ToggleActiveApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\TogglePostsApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\UpdateApplicationConditionUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplicationConditionsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $conditionQuery = Condition::query()
            ->with('stores')
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return $conditionQuery->first();
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
            return $conditionQuery->get();
        }

        return $conditionQuery->paginate($request->query('per_page') ?? 15);
    }

    public function activeIndex(Request $request)
    {
        $conditionQuery = Condition::query()
            ->active()
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return $conditionQuery->first();

        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $conditionQuery->get();
        }

        return $conditionQuery->paginate($request->query('per_page') ?? 15);
    }

    public function store(StoreApplicationConditions $request, StoreApplicationConditionUseCase $storeApplicationConditionUseCase)
    {
        $conditionDTO = new StoreConditionDTO(
            merchant_id: (int)$request->input('merchant_id'),
            store_ids: (array)$request->input('store_ids'),
            duration: $request->input('duration') ? (int)$request->input('duration') : 0,
            commission: (int)$request->input('commission'),
            special_offer: (string)$request->input('special_offer'),
            event_id: (int)$request->input('event_id'),
            discount: (int)$request->input('discount'),
            post_merchant: (bool)$request->input('post_merchant'),
            post_alifshop: (bool)$request->input('post_alifshop'),
            started_at: $request->input('started_at') ? Carbon::parse($request->input('started_at')) : null,
            finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')) : null,
            user_id: (int)$this->user->id,
            user_name: (string)$this->user->name
        );

        return $storeApplicationConditionUseCase->execute($conditionDTO);
    }

    public function massStore(MassStoreApplicationConditionsRequest $request, MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase)
    {
        $massStoreConditionDTO = new MassStoreConditionDTO(
            merchant_ids: (array)$request->input('merchant_ids'),
            template_ids: (array)$request->input('template_ids'),
            special_offer: (string)$request->input('special_offer'),
            event_id: (int)$request->input('event_id'),
            post_merchant: (bool)$request->input('post_merchant'),
            post_alifshop: (bool)$request->input('post_alifshop'),
            started_at: $request->input('started_at') ? Carbon::parse($request->input('started_at')) : null,
            finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')) : null,
            user_id: (int)$this->user->id,
            user_name: (string)$this->user->name
        );

        return $massStoreApplicationConditionUseCase->execute($massStoreConditionDTO);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request, MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase)
    {
        $massSpecialStoreConditionDTO = new MassSpecialStoreConditionDTO(
            merchant_ids: (array)$request->input('merchant_ids'),
            duration: $request->input('duration') ? (int)$request->input('duration') : 0,
            commission: (int)$request->input('commission'),
            special_offer: (string)$request->input('special_offer'),
            event_id: (int)$request->input('event_id'),
            discount: (int)$request->input('discount'),
            post_merchant: (bool)$request->input('post_merchant'),
            post_alifshop: (bool)$request->input('post_alifshop'),
            started_at: $request->input('started_at') ? Carbon::parse($request->input('started_at')) : null,
            finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')) : null,
            user_id: (int)$this->user->id,
            user_name: (string)$this->user->name
        );

        return $massSpecialStoreApplicationConditionUseCase->execute($massSpecialStoreConditionDTO);
    }

    public function update($condition_id, UpdateApplicationConditions $request, UpdateApplicationConditionUseCase $updateApplicationConditionUseCase)
    {
        $updateConditionDTO = new UpdateConditionDTO(
            store_ids: (array)$request->input('store_ids'),
            duration: $request->input('duration') ? (int)$request->input('duration') : 0,
            commission: (int)$request->input('commission'),
            special_offer: (string)$request->input('special_offer'),
            event_id: (int)$request->input('event_id'),
            discount: (int)$request->input('discount'),
            user_id: (int)$this->user->id,
            user_name: (string)$this->user->name
        );

        return $updateApplicationConditionUseCase->execute((int)$condition_id, $updateConditionDTO);
    }

    public function delete($condition_id, DeleteApplicationConditionUseCase $deleteApplicationConditionUseCase)
    {
        return $deleteApplicationConditionUseCase->execute((int)$condition_id, $this->user);
    }

    public function toggle($condition_id, ToggleActiveApplicationConditionUseCase $toggleActiveApplicationConditionUseCase)
    {
        return $toggleActiveApplicationConditionUseCase->execute((int)$condition_id, $this->user);
    }

    public function togglePosts($id, TogglePostsApplicationConditionRequest $request, TogglePostsApplicationConditionUseCase $togglePostsApplicationConditionUseCase)
    {
        return $togglePostsApplicationConditionUseCase->execute((int)$id, (bool)$request->input('post_merchant'), (bool)$request->input('post_alifshop'));
    }
}
