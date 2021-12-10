<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreDocuments;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestUploadFile;
use App\HttpServices\Company\CompanyService;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Modules\Merchants\Services\RequestStatus;
use App\Services\DistrictService;
use App\Services\LegalNameService;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerchantRequestsController extends Controller
{
    public function app()
    {
        $registration_file_types = File::$registration_file_types;
        $regions = RegionService::getRegions();
        $legal_name_prefixes = LegalNameService::getNamePrefixes();

        return [
            'registration_file_types' => $registration_file_types,
            'regions' => $regions,
            'legal_name_prefixes' => $legal_name_prefixes
        ];
    }

    public function show($id)
    {
        if (preg_match('/^\d+$/', $id)) {
            $merchant_request = MerchantRequest::with('files')->find($id);
        } else {
            $merchant_request = MerchantRequest::with('files')->where('token', $id)->first();
        }

        return $merchant_request;
    }

    public function storeMain(MerchantRequestStoreMain $request)
    {
//        user_phone
        $merchant_request = MerchantRequest::where('user_phone', $request->user_phone)->first();

        if($merchant_request) {
            throw new BusinessException('Запрос с таким номером телефона уже существует, статус запроса '
                . RequestStatus::getOneById((int) $merchant_request->status_id)->name);
        }
        $validatedRequest = $request->validated();

        if (CompanyService::getCompanyByName($request->input('name'))) {
            return response()->json(['message' => 'Указанное имя компании уже занято'], 400);
        }


        if($merchant_request = MerchantRequest::onlyByToken($request->input('token'))->first()){
            $merchant_request->fill($validatedRequest);
        } else {
            $merchant_request = new MerchantRequest();
            $merchant_request->fill($validatedRequest);
        }
        $merchant_request->setStatusNew();
        $merchant_request->save();
        $merchant_request->checkToCompleted();

        return $merchant_request;
    }

    public function storeDocuments(MerchantRequestStoreDocuments $request)
    {
        $validatedRequest = $request->validated();

        if($merchant_request = MerchantRequest::onlyByToken($request->input('token'))->first()){
            $merchant_request->fill($validatedRequest);
        } else {
            $merchant_request = new MerchantRequest();
            $merchant_request->fill($validatedRequest);
        }
        $merchant_request->save();
        $merchant_request->checkToCompleted();

        return $merchant_request;
    }

    public function upload(MerchantRequestUploadFile $request)
    {
        /** @var Merchant $merchant */
        $merchant_request = MerchantRequest::query()->where('token', $request->input('token'))->firstOrFail();
        $merchant_request_file = $merchant_request->uploadFile($request->file('file'), $request->input('file_type'));
        $merchant_request->checkToCompleted();

        return $merchant_request_file;
    }

    public function deleteFile(Request $request, $file_id)
    {
        $this->validate($request,[
            'token' => 'required|string'
        ]);

        /** @var Merchant $merchant */
        $merchant_request = MerchantRequest::query()->where('token', $request->input('token'))->firstOrFail();
        $merchant_request->deleteFile($file_id);

        return response()->json(['message' => 'Файл успешно удалён.']);
    }

    public function getDistricts(Request $request)
    {
        if($request->query('region')) {
            return DistrictService::getDistrictsByRegion($request->query('region'));

        }
        return DistrictService::getDistricts();
    }
}
