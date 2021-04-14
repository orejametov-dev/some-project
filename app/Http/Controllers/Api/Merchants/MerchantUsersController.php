<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\MerchantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantUsersController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchant_users = MerchantUser::query()->filterRequest($request);

        if($request->query('relations')) {
            $merchant_users->with($request->query('relations'));
        }

        if ($request->query('object') == 'true') {
            return $merchant_users->first();
        }
        return $merchant_users->paginate($request->query('per_page'));
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        return MerchantUser::with($request->query('relations') ?? [])->findOrFail($id);
    }

    public function getByUserId($user_id)
    {
        $merchant_user = MerchantUser::query()->with(['merchant', 'store']);

        return Cache::remember('merchant_user_id_' . $user_id , 3600, function () use ($merchant_user, $user_id) {
            return $merchant_user->byUserId($user_id)->first();
        });
    }

    public function updatePermissions($id, Request $request)
    {

        $validatedRequest = $this->validate($request, [
            'permission_applications' => 'nullable|boolean',
            'permission_deliveries' => 'nullable|boolean',
            'permission_orders' => 'nullable|boolean',
            'permission_manager' => 'nullable|boolean',
            'store_id' => 'required|integer',
        ]);


        /** @var MerchantUser $merchant_user */
        $merchant_user = MerchantUser::query()->findOrFail($id);
        $merchant = $merchant_user->merchant;

        $store = $merchant->stores()->findOrFail($request->input('store_id'));

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

        if (!isset($validatedRequest['permission_applications']))
            $validatedRequest['permission_applications'] = false;
        if (!isset($validatedRequest['permission_deliveries']))
            $validatedRequest['permission_deliveries'] = false;
        if (!isset($validatedRequest['permission_orders']))
            $validatedRequest['permission_orders'] = false;
        if (!isset($validatedRequest['permission_manager']))
            $validatedRequest['permission_orders'] = false;

        $merchant_user->fill($validatedRequest);
        $merchant_user->store()->associate($store);

        $merchant_user->save();

        Cache::forget('merchant_user_id_' . $merchant_user->user_id);

        return $merchant_user;
    }
}
