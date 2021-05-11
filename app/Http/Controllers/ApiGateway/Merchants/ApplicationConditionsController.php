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

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Создано условие',
            'keyword' => 'id: ' . $condition->id . ' ' . $condition->title,
            'action' => 'create',
            'class' => 'info',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

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

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Изменено условие',
            'keyword' => 'id: ' . $condition->id . ' ' . $condition->title,
            'action' => 'update',
            'class' => 'warning',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

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
            return response()->json(['message' => 'Условие не может быть удалено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->delete();

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Условие удалено',
            'keyword' => 'id: ' . $condition->id . ' ' . $condition->title,
            'action' => 'delete',
            'class' => 'danger',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

        return response()->json(['message' => 'Условие удалено']);
    }

    public function toggle($condition_id)
    {
        $condition = Condition::query()->findOrFail($condition_id);
        $condition->active = !$condition->active;
        $condition->save();

        $merchant = $condition->merchant;

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Изменено условие',
            'keyword' => 'id: ' . $condition->id . ' ' . $condition->title . ' на ' . ($condition->active) ? 'активный' : 'не активный',
            'action' => 'update',
            'class' => 'warning',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

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
