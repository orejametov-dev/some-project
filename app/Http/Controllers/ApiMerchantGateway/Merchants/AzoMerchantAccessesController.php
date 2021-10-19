<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Jobs\ToggleMerchantRoleOfUser;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;
use App\Services\Helpers\Randomizr;
use App\Services\SMS\OtpProtector;
use App\Services\SMS\SmsMessages;
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
            keyword: 'old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: (' . $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;
    }

    public function requestStore(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string|digits:12'
        ]);

        $phone = $request->input('phone');

        $phone_exists = AuthMicroService::getUserByPhone($phone);

        if ($phone_exists) {
            throw new BusinessException('Пользователь уже является сотрудником', 'user_exists', 404);
        }

        $phone_exists = AzoMerchantAccess::query()
            ->where('phone', $phone)
            ->exists();

        if ($phone_exists) {
            throw new BusinessException('Пользователь уже является сотрудником', 'user_exists', 404);
        }

        $otpProtector = new OtpProtector('merchant_user_' . $phone);
        $otpProtector->verifyRequestOtpCount();

        if (config('app.env') == 'production') {
            $code = Randomizr::generateOtp();
            $message = SmsMessages::onAuthentication($code);
            NotifyMicroService::sendSms($phone, $message);
        } else {
            $code = 1111;
        }

        $otpProtector->writeOtpToCache($code);

        return response()->json(['message' => 'Код подтверждения отправлен']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required|digits:4',
            'phone' => 'required|string|digits:12',
            'name' => 'required',
            'password' => 'required|string|max:255',
            'store_id' => 'required|integer'
        ]);

        $phone = $request->input('phone');
        $store = Store::query()->findOrFail($request->input('store_id'));

        $phone_exists = AuthMicroService::getUserByPhone($phone);

        if ($phone_exists) {
            throw new BusinessException('Пользователь уже является сотрудником', 'user_exists', 400);
        }

        $phone_exists = AzoMerchantAccess::query()
            ->where('phone', $phone)
            ->exists();

        if ($phone_exists) {
            throw new BusinessException('Пользователь уже является сотрудником', 'user_exists', 400);
        }

        $protector = new OtpProtector('merchant_user_' . $phone);
        $protector->verifyOtp($request->input('otp'));

        $user = AuthMicroService::createUser(
            name: $request->input('name'),
            phone: $phone,
            password: $request->input('password')
        );

        $company_user = CompanyUser::query()->where('user_id', $user['data']['id'])->firstOrNew();
        $company_user->user_id = $user['data']['id'];
        $company_user->phone = $user['data']['phone'];
        $company_user->full_name = $user['data']['name'];
        $company_user->company_id = $store->merchant->company->id;
        $company_user->save();

        $merchant = $store->merchant;

        if ($azo_merchant_access = AzoMerchantAccess::withTrashed()->where('user_id', $user['data']['id'])->first()) {
            $azo_merchant_access->restore();
        } else {
            $azo_merchant_access = new AzoMerchantAccess();
        }

        $azo_merchant_access->user_id = $user['data']['id'];
        $azo_merchant_access->user_name = $user['data']['id'];
        $azo_merchant_access->phone = $user['data']['id'];
        $azo_merchant_access->merchant()->associate($merchant);
        $azo_merchant_access->store()->associate($store->id);
        $azo_merchant_access->company_user()->associate($company_user->id);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник создан',
            keyword: 'Сотрудник добавлен в магазин: (store_id: ' . $store->id . ', store_name: ' . $store->name . ')',
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthMicroService::ACTIVATE_MERCHANT_ROLE);

        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;


    }
}
