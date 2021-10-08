<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\UpdateMerchantUsers;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccessesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantUsersQuery = AzoMerchantAccess::query()
            ->with(['merchant', 'store'])
            ->byMerchant($this->merchant_id)
            ->filterRequest($request)
            ->orderRequest($request);

        return $merchantUsersQuery->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        $merchantUser = AzoMerchantAccess::query()
            ->byMerchant($this->merchant_id)
            ->findOrFail($id);

        return $merchantUser;
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|integer'
        ]);

        $azo_merchant_access = AzoMerchantAccess::query()->findOrFail($id);
        $merchant = $azo_merchant_access->merchant;
        $old_store = $azo_merchant_access->store;
        $store = $merchant->stores()->where(['id' => $request->input('store_id')])->firstOrFail();

        $azo_merchant_access->store()->associate($store);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'MERCHANT',
            created_by_id: $this->user->id,
            body: 'Сотрудник обновлен',
            keyword:'old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: ('.  $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;
    }

}
