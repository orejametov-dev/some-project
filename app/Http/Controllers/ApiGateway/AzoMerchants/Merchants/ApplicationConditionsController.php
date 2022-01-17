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
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\ConditionTemplate;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Modules\Merchants\Models\Store;
use App\Services\Alifshop\AlifshopService;
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

    public function store(StoreApplicationConditions $request)
    {

        /** @var Merchant $merchant */
        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));

        $merchant_stores = $merchant->stores()->active()->get();

        $store_ids = $request->input('store_ids') ?? [];
        foreach ($store_ids as $id) {
            if (!$merchant_stores->where('id', $id)->first()) {
                return response()->json(['message' => 'Указан не правильный магазин'], 400);
            }
        }

        $main_store = $merchant_stores->where('is_main')->first();
        if ($request->input('post_alifshop') and !in_array($main_store->id, $store_ids)) {
            $store_ids[] = $main_store->id;
        }

        $condition = new Condition($request->validated());
        $condition->is_special = !empty($store_ids) ?? false;
        $condition->event_id = $request->input('event_id');
        $condition->merchant()->associate($merchant);
        $condition->store_id = $main_store->id;

        $condition->started_at = $request->input('started_at') ?? null;
        $condition->finished_at = $request->input('finished_at') ?? null;

        if (empty($request->input('started_at')))
        {
            $condition->active = true;
        }
        $condition->save();
        if ($store_ids) {
            $condition->stores()->attach($store_ids);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Создано условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags($merchant->id)->flush();

        return $condition->load('stores');
    }

    public function massStore(MassStoreApplicationConditionsRequest $request)
    {
        $merchants = Merchant::query()
            ->whereIn('id', $request->input('merchant_ids'))
            ->get();

        if (!empty(array_diff($request->input('merchant_ids'), $merchants->pluck('id')->toArray()))) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует'
            ], 400);
        }

        $templates = ConditionTemplate::query()
            ->whereIn('id', $request->input('template_ids'))
            ->get();

        if (!empty(array_diff($request->input('template_ids'), $templates->pluck('id')->toArray()))) {
            throw new ApiBusinessException('Условие не существует', 'merchant_not_exists', [
                'ru' => 'Условие не существует'
            ], 400);
        }

        foreach ($merchants as $merchant) {
            foreach ($templates as $template) {
                $condition = Condition::query()->where('merchant_id', $merchant->id)
                    ->where('duration', $template->duration)
                    ->where('commission', $template->commission)
                    ->exists();

                if ($condition) {
                    throw new BusinessException('Данное условие существует для этого мерчанта '
                        . $merchant->name . ' ' . $template->duration . '|' . $template->commission . '%',
                        'condition_exists', 400);
                }
            }
        }

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();
            foreach ($templates as $template) {

                $condition = new Condition();
                $condition->duration = $template->duration;
                $condition->commission = $template->commission;
                $condition->event_id = $request->input('event_id');
                $condition->merchant()->associate($merchant);
                $condition->store_id = $main_store->id;
                $condition->started_at = $request->input('started_at') ?? null;
                $condition->finished_at = $request->input('finished_at') ?? null;

                if (empty($request->input('started_at')))
                {
                    $condition->active = true;
                }

                $condition->save();

                SendHook::dispatch(new HookData(
                    service: 'merchants',
                    hookable_type: $merchant->getTable(),
                    hookable_id: $merchant->id,
                    created_from_str: 'PRM',
                    created_by_id: $this->user->id,
                    body: 'Создано условие',
                    keyword: 'id: ' . $condition->id . ' ' . $condition->title,
                    action: 'create',
                    class: 'info',
                    action_at: null,
                    created_by_str: $this->user->name,
                ));
            }
            Cache::tags($merchant->id)->flush();
        }

        return response()->json(['message' => 'Условия изменены']);
    }

    public function massSpecialStore(MassSpecialStoreApplicationConditionRequest $request)
    {
        $merchants = Merchant::query()
            ->whereIn('id', $request->input('merchant_ids'))
            ->get();

        if (!empty(array_diff($request->input('merchant_ids'), $merchants->pluck('id')->toArray()))) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует'
            ], 400);
        }

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();

            $condition = new Condition($request->validated());
            $condition->event_id = $request->input('event_id');
            $condition->merchant()->associate($merchant);
            $condition->store_id = $main_store->id;
            $condition->started_at = $request->input('started_at') ?? null;
            $condition->finished_at = $request->input('finished_at') ?? null;

            if (empty($request->input('started_at')))
            {
                $condition->active = true;
            }

            $condition->save();

            SendHook::dispatch(new HookData(
                service: 'merchants',
                hookable_type: $merchant->getTable(),
                hookable_id: $merchant->id,
                created_from_str: 'PRM',
                created_by_id: $this->user->id,
                body: 'Создано условие',
                keyword: 'id: ' . $condition->id . ' ' . $condition->title,
                action: 'create',
                class: 'info',
                action_at: null,
                created_by_str: $this->user->name,
            ));

            Cache::tags($merchant->id)->flush();
        }

        return response()->json(['message' => 'Условия изменены']);
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
