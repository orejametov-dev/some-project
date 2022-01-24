<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Applications\MassSpecialStoreApplicationConditionRequest;
use App\Http\Requests\ApiPrm\Applications\MassStoreApplicationConditionsRequest;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditions;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditions;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\Conditions\MassSpecialStoreConditionDTO;
use App\Modules\Merchants\DTO\Conditions\MassStoreConditionDTO;
use App\Modules\Merchants\DTO\Conditions\StoreConditionDTO;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\ConditionTemplate;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Modules\Merchants\Models\Store;
use App\Services\Alifshop\AlifshopService;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

    public function store(StoreApplicationConditions $request ,  StoreApplicationConditionUseCase $storeApplicationConditionUseCase)
    {
        $conditionDTO = new StoreConditionDTO(
            merchant_id: (int) $request->input('merchant_id'),
            store_ids: (array) $request->input('store_ids'),
            duration: $request->input('duration') ? (int) $request->input('duration'): 0,
            commission: (int) $request->input('commission'),
            special_offer: (string) $request->input('special_offer'),
            event_id: (int) $request->input('event_id'),
            discount: (int) $request->input('discount'),
            post_merchant: (bool) $request->input('post_merchant'),
            post_alifshop: (bool) $request->input('post_alifshop') ,
            started_at:  $request->input('started_at') ? Carbon::parse($request->input('started_at')): null,
            finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')): null,
            user_id: (int) $this->user->id,
            user_name: (string) $this->user->name
        );

        return $storeApplicationConditionUseCase->execute($conditionDTO);
    }

    public function massStore(MassStoreApplicationConditionsRequest $request , MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase)
    {
       $massStoreConditionDTO = new MassStoreConditionDTO(
           merchant_ids: (array) $request->input('merchant_ids'),
           template_ids: (array) $request->input('template_ids'),
           special_offer: (string) $request->input('special_offer'),
           event_id: (int) $request->input('event_id'),
           post_merchant: (bool) $request->input('post_merchant'),
           post_alifshop: (bool) $request->input('post_alifshop') ,
           started_at:  $request->input('started_at') ? Carbon::parse($request->input('started_at')): null,
           finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')): null,
           user_id: (int) $this->user->id,
           user_name: (string) $this->user->name
       );

       return $massStoreApplicationConditionUseCase->execute($massStoreConditionDTO);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request, MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase)
    {
        $massSpecialStoreConditionDTO = new MassSpecialStoreConditionDTO(
            merchant_ids: (array) $request->input('merchant_ids'),
            duration: $request->input('duration') ? (int) $request->input('duration'): 0,
            commission: (int) $request->input('commission'),
            special_offer: (string) $request->input('special_offer'),
            event_id: (int) $request->input('event_id'),
            discount: (int) $request->input('discount'),
            post_merchant: (bool) $request->input('post_merchant'),
            post_alifshop: (bool) $request->input('post_alifshop') ,
            started_at:  $request->input('started_at') ? Carbon::parse($request->input('started_at')): null,
            finished_at: $request->input('finished_at') ? Carbon::parse($request->input('finished_at')): null,
            user_id: (int) $this->user->id,
            user_name: (string) $this->user->name
        );

        return $massSpecialStoreApplicationConditionUseCase->execute($massSpecialStoreConditionDTO);
    }

    public function update(UpdateApplicationConditions $request, $condition_id)
    {
        /** @var Condition $condition */
        $condition = Condition::query()->findOrFail($condition_id);

        $applications = CoreService::getApplicationConditionId($condition_id);

        if ($applications) {
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }
        $merchant = $condition->merchant;

        $merchant_stores = $merchant->stores()->active()->get();

        $store_ids = $request->input('store_ids') ?? [];
        foreach ($store_ids as $id) {
            if (!$merchant_stores->where('id', $id)->first()) {
                return response()->json(['message' => 'Указан не правильный магазин'], 400);
            }
        }

        $main_store = $merchant_stores->where('is_main')->first();
        if ($request->input('post_alifshop') and !in_array($main_store->id, $store_ids)) {
            return response()->json(['message' => 'Для онлайн заявок надо указать основной магазин'], 400);
        }

        $condition->stores()->detach();
        $condition->stores()->attach($store_ids);

        $condition->fill($request->validated());
        $condition->event_id = $request->input('event_id');
        $condition->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags($merchant->id)->flush();

        return $condition;
    }

    public function delete($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);

        $applications = CoreService::getApplicationConditionId($condition_id);

        if ($applications) {
            return response()->json(['message' => 'Условие не может быть удалено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->delete();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Условие удалено',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags($merchant->id)->flush();

        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);
        $condition->active = !$condition->active;
        $condition->save();

        $merchant = $condition->merchant;

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title . ' на ' . ($condition->active) ? 'активный' : 'не активный',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        $merchant->load(['application_conditions' => function ($q) {
            $q->active();
        }]);

        $conditions = $merchant->application_conditions->where('post_alifshop', true)->map(function ($item) {
            return [
                'id' => $item->id,
                'commission' => $item->commission,
                'duration' => $item->duration,
                'event_id' => $item->event_id
            ];
        });

        $alifshopService = new AlifshopService;
        $alifshopService->storeOrUpdateConditions($merchant->company_id, $conditions);

        Cache::tags($merchant->id)->flush();

        return $condition;
    }

    public function togglePosts($id, Request $request)
    {
        $this->validate($request, [
            'post_alifshop' => 'required|boolean',
            'post_merchant' => 'required|boolean'
        ]);

        /** @var Condition $condition */
        $condition = Condition::query()->findOrFail($id);

        $merchant = $condition->merchant;
        $main_store = $merchant->stores()->main()->exists();

        if ($request->input('post_alifshop') and !$main_store) {
            return response()->json(['message' => 'Для онлайн заявок надо указать основной магазин'], 400);
        }

        $condition->post_merchant = $request->input('post_merchant');
        $condition->post_alifshop = $request->input('post_alifshop');
        $condition->save();

        $merchant->load(['application_conditions' => function ($q) {
            $q->active();
        }]);

        $conditions = $merchant->application_conditions->where('post_alifshop', true)->map(function ($item) {
            return [
                'id' => $item->id,
                'commission' => $item->commission,
                'duration' => $item->duration,
                'event_id' => $item->event_id
            ];
        });

        $alifshopService = new AlifshopService;
        $alifshopService->storeOrUpdateConditions($merchant->company_id, $conditions);

        Cache::tags($merchant->id)->flush();

        return $condition;
    }
}
