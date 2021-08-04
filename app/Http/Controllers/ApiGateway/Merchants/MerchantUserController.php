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
        $merchantUsersQuery = MerchantUser::query()
            ->with(['merchant', 'store'])
            ->filterRequest($request)
            ->orderRequest($request);


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
        $user = ServiceCore::request('GET', 'users/'.$request->input('user_id'), null);

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
        $merchant_user->user_name = $user->name;
        $merchant_user->phone = $user->phone;
        $merchant_user->merchant()->associate($merchant);
        $merchant_user->store()->associate($store->id);

        $merchant_user->save();

        ServiceCore::storeHook(
            'Сотрудник создан',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'create',
            'info',
            $merchant
        );

        Cache::tags('merchants')->forget('merchant_user_id_' . $merchant_user->user_id);
        Cache::tags($merchant->id)->flush();

        return $merchant_user;
    }

    public function destroy($id)
    {
        $merchant_user = MerchantUser::query()->findOrFail($id);

        $merchant_user->delete();

        $merchant = $merchant_user->merchant;

        ServiceCore::storeHook(
            'Сотрудник удален',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'delete',
            'danger',
            $merchant
        );

        Cache::tags('merchants')->forget('merchant_user_id_' . $merchant_user->user_id);
        Cache::tags($merchant->id)->flush();


        return response()->json(['message' => 'Сотрудник удален']);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|integer'
        ]);

        $merchant_user = MerchantUser::query()->findOrFail($id);
        $merchant = $merchant_user->merchant;
        $store = $merchant->stores()->where(['id' => $request->input('store_id')])->firstOrFail();

        $merchant_user->store()->associate($store);

        $merchant_user->save();

        ServiceCore::storeHook(
            'Сотрудник обновлен',
            'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id,
            'update',
            'warning',
            $merchant
        );

        Cache::tags('merchants')->forget('merchant_user_id_' . $merchant_user->user_id);
        Cache::tags($merchant->id)->flush();

        return $merchant_user;
    }
}
