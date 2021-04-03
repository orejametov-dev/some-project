<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStore;
use App\Modules\Core\Models\Comment;
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
        $this->alifshopService = $alifshopService;
    }

    public function index(Request $request)
    {
        $merchantRequests = MerchantRequest::query()
            ->orderRequest($request)->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $merchantRequests->first();
        }
        $paginated_requests = $merchantRequests->paginate($request->query('per_page'));

        $engages = ServiceCore::request('GET', 'users', new Request([
            'user_ids' => implode(';', $paginated_requests->pluck('engaged_by_id')->toArray()),
        ]));

        foreach ($paginated_requests as $request) {
            $request->engaged_by = collect($engages)->where('id', $request->engaged_by_id)->first();
        }

        return $paginated_requests;
    }

    public function show($id)
    {
        $request = MerchantRequest::query()->findOrFail($id);

        $user = ServiceCore::request('GET', 'users', new Request([
            'id' => $request->engaged_by_id,
            'object' => 'true'
        ]));
        $request->engaged_by = $user;

        return $request;
    }

    public function store(MerchantRequestStore $request)
    {
        $user = ServiceCore::request('GET', 'users', new Request([
            'q' => $request->input('user_phone'),
            'object' => 'true',
        ]));

        if ($user)
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

        ServiceCore::request('POST', 'model-hooks', new Request([
            'body' => 'Изменен статус на',
            'keyword' => 'новый',
            'action' => 'store',
            'model' => [
                'id' => $merchant_request->id,
                'table_name' => $merchant_request->getTable()
            ],
            'created_by_id' => $this->user->id
        ]));

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

        $user = ServiceCore::request('GET', 'users', new Request([
            'user_id' => $request->input('engaged_by_id'),
            'object' => 'true'
        ]));

        $merchant_request = MerchantRequest::findOrFail($id);

        if ($merchant_request->isStatusNew() || $merchant_request->isInProcess()) {
            $merchant_request->engaged_by_id = $request->input('engaged_by_id');
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

        $comment = new Comment();
        $comment->body = $request->input('body');
        $comment->commentable_type = MerchantRequest::TABLE_NAME;
        $comment->commentable_id = $id;
        $comment->save();

        return $merchant_request;
    }
}
