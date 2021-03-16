<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Applications\StoreApplicationConditions;
use App\Http\Requests\ApiPrm\Applications\UpdateApplicationConditions;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Core\Models\ModelHook;
use App\Services\Alifshop\AlifshopService;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;

class ApplicationConditionsController extends Controller
{
    public function index(Request $request)
    {
        $conditionQuery = Condition::query()->filterRequest($request)->orderRequest($request);
        if ($request->query('object') == true) {
            return $conditionQuery->first();
        }
        return $conditionQuery->paginate($request->query('per_page'));
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

        ModelHook::make($merchant,
            'Создано условие',
            'id: ' . $condition->id . ' ' . $condition->title,
            'create',
            'info'
        );

        return $condition;
    }

    public function update(UpdateApplicationConditions $request, $condition_id)
    {
        /** @var Condition $condition */
        $condition = Condition::query()->findOrFail($condition_id);

        $application = ServiceCore::request('GET', 'applications', new Request([
            'condition_id' => $condition_id,
            'object' => 'true'
        ]));

        if ($application) { //TODO заменить на HTTP
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }


        $merchant = $condition->merchant;

        $condition->fill($request->validated());
        $condition->save();

        ModelHook::make($merchant,
            'Изменено условие',
            'id: ' . $condition->id . ' ' . $condition->title,
            'update',
            'warning'
        );

        return $condition;
    }

    public function delete($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);

        $application = ServiceCore::request('GET', 'applications', new Request([
            'condition_id' => $condition_id,
            'object' => 'true'
        ]));

        if ($application) {
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->delete();

        ModelHook::make($merchant,
            'Условие удалено',
            'id: ' . $condition->id . ' ' . $condition->title,
            'delete',
            'danger'
        );
        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);
        $condition->active = !$condition->active;
        $condition->save();

        $merchant = $condition->merchant;

        ModelHook::make($merchant,
            'Изменено условие',
            'id: ' . $condition->id . ' ' . $condition->title . ' на ' . ($condition->active) ? 'активный' : 'не активный',
            'update',
            'warning'
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

        $alifshopService = new AlifshopService;
        $alifshopService->storeOrUpdateMerchant($merchant, $conditions);
        return $condition;
    }
}
