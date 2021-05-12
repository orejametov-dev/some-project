<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStore;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\Alifshop\AlifshopService;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;

class MerchantRequestsController extends ApiBaseController
{
    /**
     * @var AlifshopService
     */
    private $alifshopService;

    public function __construct(AlifshopService $alifshopService)
    {
        parent::__construct();
        $this->alifshopService = $alifshopService;
    }

    public function index(Request $request)
    {
        $merchantRequests = MerchantRequest::query()
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == 'true') {
            return $merchantRequests->first();
        }
        return $merchantRequests->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        $request = MerchantRequest::query()->findOrFail($id);
        return $request;
    }

    public function store(MerchantRequestStore $request)
    {
        $merchant_request = new MerchantRequest([
            'name' => $request->input('merchant_name'),
            'information' => $request->input('merchant_information'),
            'legal_name' => $request->input('merchant_legal_name'),

            'user_phone' => $request->input('user_phone'),
            'user_name' => $request->input('user_name'),
            'region' => $request->input('region')
        ]);
        $merchant_request->setStatusNew();
        $merchant_request->save();



        return response()->json([
            'code' => 'merchant_request_created',
            'message' => 'Запрос на регистрацию отправлен. В ближайшее время с вами свяжется сотрудник Alifshop.'
        ]);
    }

    public function setEngage(Request $request, $id)
    {
        $this->validate($request, [
            'engaged_by_id' => 'required|integer'
        ]);

        $user = ServiceCore::request('GET', 'users', [
            'user_id' => $request->input('engaged_by_id'),
            'object' => 'true'
        ]);

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $merchant_request = MerchantRequest::findOrFail($id);

        if ($merchant_request->isStatusNew() || $merchant_request->isInProcess()) {
            $merchant_request->engaged_by_id = $request->input('engaged_by_id');
            $merchant_request->engaged_by_name = $user->name;
            $merchant_request->engaged_at = now();
            $merchant_request->setStatusInProcess();
            $merchant_request->save();

            $merchant_request->engaged_by = $user;

            return $merchant_request;
        }

        return response()->json(['message' => 'Не возможно менять статус']);
    }

    public function allow($id)
    {
        $merchant_request = MerchantRequest::findOrFail($id);

        if (!$merchant_request->isInProcess()) {
            return response()->json(['message' => 'Статус заявки должен быть "На переговорах"'], 400);
        }

        $merchant_name_exists = Merchant::query()->where('name', $merchant_request->name)->exists();
        if ($merchant_name_exists) {
            return response()->json(['message' => 'Указанное имя партнера уже занято'], 400);
        }

        $merchant_request->setStatusAllowed();
        $merchant_request->save();

        return $merchant_request;
    }

    public function reject(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $merchant_request = MerchantRequest::findOrFail($id);

        if (!$merchant_request->isInProcess()) {
            return response()->json(['message' => 'Статус заявки должен быть "На переговорах"'], 400);
        }

        $merchant_request->setStatusTrash();
        $merchant_request->save();

        return $merchant_request;
    }
}
