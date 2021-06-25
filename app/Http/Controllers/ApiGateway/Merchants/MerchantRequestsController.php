<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Resources\ApiPrmGateway\Merchants\MerchantRequestsResource;
use App\Modules\Merchants\DTO\Merchants\MerchantInfoDTO;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Modules\Merchants\Models\Tag;
use App\Modules\Merchants\Services\Merchants\MerchantsService;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerchantRequestsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantRequests = MerchantRequest::query()
            ->filterRequest($request)
            ->orderRequest($request)
            ->onlyCompletedRequests($request);


        if ($request->query('object') == true) {
            return $merchantRequests->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $merchantRequests->get();
        }

        return MerchantRequestsResource::collection($merchantRequests->paginate($request->query('per_page') ?? 15));
    }

    public function show($id)
    {
        $merchant_request = MerchantRequest::query()->with('files')->findOrFail($id);
        return $merchant_request;
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
            $merchant_request->setEngage($user);
            $merchant_request->setStatusInProcess();
            $merchant_request->save();

            $merchant_request->engaged_by = $user;

            return $merchant_request;
        }

        return response()->json(['message' => 'Не возможно менять статус']);
    }

    public function allow($id, MerchantsService $merchantsService)
    {
        $merchant_request = MerchantRequest::findOrFail($id);

        if (!$merchant_request->isInProcess()) {
            return response()->json(['message' => 'Статус заявки должен быть "На переговорах"'], 400);
        }

        $merchant_name_exists = Merchant::query()->where('name', $merchant_request->name)->exists();
        if ($merchant_name_exists) {
            return response()->json(['message' => 'Указанное имя партнера уже занято'], 400);
        }


        DB::transaction(function () use ($merchantsService, $merchant_request){
            $merchant = $merchantsService->create(new MerchantsDTO(
                $merchant_request->name,
                $merchant_request->legal_name,
                $merchant_request->information,
                $merchant_request->engaged_by_id
            ));

            $merchant_request->files()->update(['merchant_id' => $merchant->id]);
            $merchantsService->createMerchantInfo((new MerchantInfoDTO())->fromMerchantRequest($merchant_request), $merchant);
            $ids = Tag::whereIn('title' ,$merchant_request->categories)->pluck('id');
            $merchant->tags()->attach($ids);
            $merchant_request->setStatusAllowed();
            $merchant_request->save();
        });

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
