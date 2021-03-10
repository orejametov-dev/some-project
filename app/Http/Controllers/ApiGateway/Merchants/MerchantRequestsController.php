<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStore;
use App\Modules\Core\Models\Comment;
use App\Modules\Core\Models\ModelHook;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\Alifshop\AlifshopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MerchantRequestsController extends Controller
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
        $merchants = MerchantRequest::query()
            ->orderRequest($request)->filterRequest($request);
        //TODO сделать запрос что бы получить user списки для engaged_by
        if ($request->query('object') == 'true') {
            return $merchants->first();
        }
        return $merchants->paginate($request->query('per_page'));

    }

    public function show($id)
    {
        return MerchantRequest::query()->with(['status', 'engaged_by'])->findOrFail($id);
    }

    public function store(MerchantRequestStore $request)
    {
        //Заменить на HTTP
//        $user = User::query()->where('phone', $request->user_phone)->first();
//        if (optional($user)->isMerchantUser()) {
//            return response()->json(['message' => 'Пользователь с текущим номером телефона уже является партнером.'], 400);
//        }

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

        ModelHook::make(
            $merchant_request,
            'Изменен статус на',
            'новый',
            'store'
        );

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
        $merchant_request = MerchantRequest::with('engaged_by')->findOrFail($id);

        if($merchant_request->isStatusNew() || $merchant_request->isInProcess()) {
            $merchant_request->engaged_by_id = $request->input('engaged_by_id');
            $merchant_request->engaged_at = now();
            $merchant_request->setStatusInProcess();
            $merchant_request->save();

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

        return $request;
    }
}
