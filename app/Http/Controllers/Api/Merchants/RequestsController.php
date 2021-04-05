<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function register(Request $request)
    {
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
