<?php


namespace App\Http\Controllers\ApiGateway\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUsers;
use App\Modules\Merchants\Models\MerchantUser;
use App\Modules\Merchants\Models\Store;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantUserController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantUsersQuery = MerchantUser::query()->with(['merchant', 'user'])
            ->filterRequest($request)->orderRequest($request);


        if ($request->query('object') == true) {
            return $merchantUsersQuery->first();
        }

        return $merchantUsersQuery->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        $merchant_user = MerchantUser::with(['merchant', 'store'])->findOrFail($id);
        return $merchant_user;
    }

    public function store(StoreMerchantUsers $request)
    {
        $user = ServiceCore::request('GET', 'users', new Request([
            'id' => $request->input('user_id'),
            'object' => 'true'
        ]));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $merchant_user_exists = MerchantUser::query()
            ->where(['user_id' => $request->input('user_id')])
            ->exists();

        if ($merchant_user_exists) {
            return response()->json([
                'code' => 'user_already_exists',
                'message' => 'Пользователь является сотрудником другого мерчанта.'
            ], 400);
        }

        $store = Store::query()->findOrFail($request->input('store_id'));

        $merchant = $store->merchant;
        $merchant_user = new MerchantUser();
        $merchant_user->user_id = $request->input('user_id');
        $merchant_user->merchant()->associate($merchant);
        $merchant_user->store()->associate($store->id);

        $merchant_user->save();

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Сотрудник создан',
            'keyword' => 'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'action' => 'create',
            'class' => 'info',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

        Cache::forget('merchant_user_id_' . $merchant_user->user_id);

        return $merchant_user;
    }

    public function update($id, UpdateMerchantUsers $request)
    {

        /** @var MerchantUser $merchant_user */
        $merchant_user = MerchantUser::query()->findOrFail($id);
        $merchant = $merchant_user->merchant;
        $store = $merchant->stores()->where(['id' => $request->input('store_id')])->firstOrFail();

        if ($request->input('permission_applications') == true && !$merchant->has_applications) {
            return response()->json([
                'code' => 'module_is_not_switched_on',
                'message' => 'Невозможно включить разрешение заявок, т.к. соответствующий модуль у партнера отключен.'
            ], 400);
        }
        if ($request->input('permission_deliveries') == true && !$merchant->has_deliveries) {
            return response()->json([
                'code' => 'module_is_not_switched_on',
                'message' => 'Невозможно включить разрешение доставок, т.к. соответствующий модуль у партнера отключен.'
            ], 400);
        }
        if ($request->input('permission_orders') == true && !$merchant->has_orders) {
            return response()->json([
                'code' => 'module_is_not_switched_on',
                'message' => 'Невозможно включить разрешение заказов, т.к. соответствующий модуль у партнера отключен.'
            ], 400);
        }
        if ($request->input('permission_manager') == true && !$merchant->has_manager) {
            return response()->json([
                'code' => 'module_is_not_switched_on',
                'message' => 'Невозможно включить разрешение менеджера, т.к. соответствующий модуль у партнера отключен.'
            ], 400);
        }

        $merchant_user->fill($request->validated());
        $merchant_user->store()->associate($store);

        $merchant_user->save();

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Сотрудник обновлен',
            'keyword' => 'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'action' => 'update',
            'class' => 'warning',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

        Cache::forget('merchant_user_id_' . $merchant_user->user_id);

        return $merchant_user;
    }

    public function destroy($id)
    {
        $merchant_user = MerchantUser::query()->findOrFail($id);

        $merchant_user->delete();

        $merchant = $merchant_user->merchant;

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Сотрудник удален',
            'keyword' => 'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'action' => 'delete',
            'class' => 'danger',
            'model' => [
                'id' => $merchant->id,
                'table_name' => $merchant->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

        Cache::forget('merchant_user_id_' . $merchant_user->user_id);

        return response()->json(['message' => 'Сотрудник удален']);
    }
}
