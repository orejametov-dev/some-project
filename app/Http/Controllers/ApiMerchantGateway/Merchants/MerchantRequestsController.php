<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreDocuments;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestUploadFile;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\DistrictService;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerchantRequestsController extends Controller
{
    public function app()
    {
        $registration_file_types = File::$registration_file_types;
        $regions = RegionService::getRegions();
        $districts = DistrictService::getDistricts();

        return [
            'registration_file_types' => $registration_file_types,
            'regions' => $regions,
            'districts' => $districts
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
        $validatedRequest = $request->validated();
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
