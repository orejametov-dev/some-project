<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Exceptions\ApiBusinessException;
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

        if (AzoMerchantAccess::query()->where('phone' , $request->input('phone'))->exists()) {
            throw new ApiBusinessException('Пользователь с данным номером существует' , 'phone_exists', [
                'ru' => 'Пользователь с данным номером существует',
                'uz' => 'Bunday raqam egasi tizimda mavjud'
            ],400);
        }

        $otpProtector = new OtpProtector('new_azo_merchant_user_' . $request->input('phone'));
        $otpProtector->verifyRequestOtpCount();

        if (config('app.env') == 'production') {
            $code = Randomizr::generateOtp();
            $message = SmsMessages::onAuthentication($code);
            NotifyMicroService::sendSms($request->input('phone'), $message);
        } else {
            $code = 1111;
        }

        $otpProtector->writeOtpToCache($code);

        return response()->json(['code' => 'otp_sent',
            'message' => [
                    'ru' =>'Код подтверждения отправлен',
                    'uz' => 'Tasdiqlash kodi yuborildi'
                ]]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|digits:4',
            'user_id' => 'required|integer',
            'store_id' => 'required|integer'
        ]);

        $user = AuthMicroService::getUserById($request->input('user_id'));

        $protector = new OtpProtector('new_azo_merchant_user_' . $user['data']['phone']);
        $protector->verifyOtp($request->input('code'));

        if (array_search(AuthMicroService::AZO_MERCHANT_ROLE, array_column($user['data']['roles'], 'name'))) {
            throw new ApiBusinessException('Пользователь уже является сотрудником мерчанта', 'merchant_exists', [
                'ru' => 'Пользователь уже является сотрудником мерчанта',
                'uz' => 'Foydalanuvchi merchant tizimiga bog\'langan'
            ],400);
        }

        if (AzoMerchantAccess::query()->where('phone' , $user['data']['phone'])->exists()) {
            throw new ApiBusinessException('Пользователь с данным номером существует' , 'phone_exists', [
                'ru' => 'Пользователь с данным номером существует',
                'uz' => 'Bunday raqam egasi tizimda mavjud'
            ],400);
        }

        $store = Store::query()->findOrFail($request->input('store_id'));

        $company_user = CompanyUser::query()->where('user_id', $user['data']['id'])->firstOrNew();
        $company_user->user_id = $user['data']['id'];
        $company_user->phone = $user['data']['phone'];
        $company_user->full_name = $user['data']['name'];
        $company_user->company_id = $store->merchant->company->id;
        $company_user->save();

        if (AzoMerchantAccess::query()->where('company_user_id', $company_user->id)->exists()) {
            throw new ApiBusinessException('Пользователь уже существует', 'user_exists', [
                'ru' => 'Пользователь уже существует',
                'uz' => 'Foydalanuvchi tizimda mavjud'
            ],400);
        }

        $merchant = $store->merchant;

        if ($azo_merchant_access = AzoMerchantAccess::withTrashed()->where('company_user_id', $company_user->id)->first()) {
            $azo_merchant_access->restore();
        } else {
            $azo_merchant_access = new AzoMerchantAccess();
        }

        $azo_merchant_access->user_id = $user['data']['id'];
        $azo_merchant_access->user_name = $user['data']['name'];
        $azo_merchant_access->phone = $user['data']['phone'];
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

    public function destroy($id)
    {
        $azo_merchant_access = AzoMerchantAccess::query()->findOrFail($id);
        $store = $azo_merchant_access->store;

        $azo_merchant_access->delete();

        $merchant = $azo_merchant_access->merchant;

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник удален',
            keyword: 'Сотрудник удален из магазина: (' . $store->id . ', ' . $azo_merchant_access->store->name . ')',
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthMicroService::DEACTIVATE_MERCHANT_ROLE);

        return response()->json(['message' => [
            'ru' => 'Сотрудник удален',
            'uz'  => 'Xodim o\'chirildi'
        ]]);
    }
}
