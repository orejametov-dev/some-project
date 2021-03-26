<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Modules\Core\Models\ModelHook;
use App\Modules\Core\Models\WebService;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\Core\ServiceCore;
use App\Services\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RequestsController extends Controller
{
    public function register(Request $request)
    {

        $user = ServiceCore::request('GET', 'users', new Request([
            'q' => $request->input('user_phone'),
            'object' => 'true',
        ]));

        if($user)
            throw new BusinessException(
                'Пользователь с таким номером уже существует',
                'user_already_exists',
                400);

        $merchant_request = new MerchantRequest([
            'name' => $request->input('merchant_name'),
            'information' => $request->input('merchant_information'),
            'legal_name' => $request->input('merchant_legal_name'),

            'user_phone' => $request->input('user_phone'),
            'user_name' => $request->input('user_name'),
            'region' => $request->region
        ]);
        $merchant_request->setStatusNew();
        $merchant_request->save();

        return response()->json([
            'code' => 'merchant_request_created',
            'message' => 'Запрос на регистрацию отправлен. В ближайшее время с вами свяжется сотрудник Alifshop.'
        ]);
    }
}
