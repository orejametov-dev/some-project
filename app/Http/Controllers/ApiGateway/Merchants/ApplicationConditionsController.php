<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditions;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditions;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Services\Alifshop\AlifshopService;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConditionsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $conditionQuery = Condition::query()
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
        $store = $merchant->stores()->main()->first();
        if (!$store) {
            return response()->json(['message' => 'Пожалуйста, укажите сначала основной магазин'], 400);
        }
        $condition = new Condition($request->all());
        $condition->merchant()->associate($merchant);
        $condition->store()->associate($store);
        $condition->save();

        ServiceCore::storeHook(
            'Создано условие',
            'id: ' . $condition->id . ' ' . $condition->title,
            'create',
            'info',
            $merchant
        );

        Cache::tags($merchant->id)->flush();

        return $condition;
    }

    public function update(UpdateApplicationConditions $request, $condition_id)
    {
        /** @var Condition $condition */
        $condition = Condition::query()->findOrFail($condition_id);

        $applications = ServiceCore::request('GET', 'applications/count',null);

        if ($applications) {
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->fill($request->validated());
        $condition->save();

        ServiceCore::storeHook(
            'Изменено условие',
            'id: ' . $condition->id . ' ' . $condition->title,
            'update',
            'warning',
            $merchant
        );

        Cache::tags($merchant->id)->flush();

        return $condition;
    }

    public function delete($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);

        $applications = ServiceCore::request('GET', 'applications/count',null);


        if ($applications) {
            return response()->json(['message' => 'Условие не может быть удалено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->delete();

        ServiceCore::storeHook(
            'Условие удалено',
            'id: ' . $condition->id . ' ' . $condition->title,
            'delete',
            'danger',
            $merchant
        );

        Cache::tags($merchant->id)->flush();

        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);
        $condition->active = !$condition->active;
        $condition->save();

        $merchant = $condition->merchant;

        ServiceCore::storeHook(
            'Изменено условие',
            'id: ' . $condition->id . ' ' . $condition->title . ' на ' . ($condition->active) ? 'активный' : 'не активный',
            'update',
            'warning',
            $merchant
        );

        $merchant->load(['application_conditions' => function ($q) {
            $q->active();
        }]);

        $conditions = $merchant->application_conditions->map(function ($item) {
            return [
                'commission' => $item->commission,
                'duration' => $item->duration,
                'is_active' => $item->active,
                'special_offer' => $item->special_offer
            ];
        });

        Cache::tags($merchant->id)->flush();

        $alifshopService = new AlifshopService;
        $alifshopService->storeOrUpdateMerchant($merchant, $conditions);
        return $condition;
    }
}
