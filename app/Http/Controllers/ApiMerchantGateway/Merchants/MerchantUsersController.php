<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUsers;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
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
        $old_store = $merchant_user->store;
        $store = $merchant->stores()->where(['id' => $request->input('store_id')])->firstOrFail();

        $merchant_user->store()->associate($store);

        $merchant_user->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant_user->getTable(),
            hookable_id: $merchant_user->id,
            created_from_str: 'MERCHANT',
            created_by_id: $this->user->id,
            body: 'Сотрудник обновлен',
            keyword:'merchant_user_id: ' . $merchant_user->id . ' user_id: ' . $merchant_user->user_id . ' old_store: ('
            . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: ('.  $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        Cache::tags('merchants')->forget('merchant_user_id_' . $merchant_user->user_id);
        Cache::tags($merchant->id)->flush();

        return $merchant_user;
    }

}
