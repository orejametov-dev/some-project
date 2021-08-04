<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUsers;
use App\Modules\Merchants\Models\MerchantUser;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantUsersController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantUsersQuery = MerchantUser::query()
            ->with(['merchant', 'store'])
            ->byMerchant($this->merchant_id)
            ->filterRequest($request)
            ->orderRequest($request);

        return $merchantUsersQuery->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        $merchantUser = MerchantUser::query()
            ->byMerchant($this->merchant_id)
            ->findOrFail($id);

        return $merchantUser;
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
