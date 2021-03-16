<?php


namespace App\Http\Controllers\ApiGateway\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUsers;
use App\Modules\Core\Models\ModelHook;
use App\Modules\Merchants\Models\MerchantUser;
use App\Modules\Merchants\Models\Store;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;

class MerchantUserController extends Controller
{
    public function index(Request $request)
    {
        $merchantUsersQuery = MerchantUser::query()->with('store')
            ->filterRequest($request)->orderRequest($request);


        if ($request->query('object') == true) {
            return $merchantUsersQuery->first();
        }

        $paginatedMerchantUsers = $merchantUsersQuery->paginate($request->query('per_page'));

        $users = ServiceCore::request('GET', 'users', new Request([
            'user_ids' => implode(';', $paginatedMerchantUsers->pluck('user_id')->toArray()),
        ]));

        foreach ($paginatedMerchantUsers as $merchantUser) {
            $merchantUser->user = collect($users)->where('id', $merchantUser->user_id)->first();
        }

        return $paginatedMerchantUsers;
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

//        ServiceCore::request('POST', 'merchant-users', new Request([
//            'merchant_id' => $merchant->id,
//            'store_id' => $store->id,
//            'user_id' => $merchant_user->user_id
//        ]));

        $merchant_user->save();

        ModelHook::make($merchant,
            'Сотрудник создан',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'create',
            'info'
        );

        return $merchant_user;
    }

    public function update($id, UpdateMerchantUsers $request)
    {
        $validatedRequest = $request->validated();

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

//        ServiceCore::request('POST', 'merchant-users/' . $merchant_user->id, new Request($request->validated()));

        $merchant_user->save();

        ModelHook::make($merchant,
            'Сотрудник обновлен',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'update',
            'warning'
        );
        return $merchant_user;
    }

    public function destroy($id)
    {
        $merchant_user = MerchantUser::query()->findOrFail($id);

//        ServiceCore::request('DELETE', 'merchant-users/' . $merchant_user->id);

        $merchant_user->delete();

        $merchant = $merchant_user->merchant;

        ModelHook::make($merchant,
            'Сотрудник удален',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'delete',
            'danger'
        );
        return response()->json(['message' => 'Сотрудник удален']);
    }
}
