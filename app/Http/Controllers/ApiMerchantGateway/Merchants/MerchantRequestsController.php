<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerchantRequestsController extends Controller
{
    public function app()
    {
        $registration_file_types = File::$registration_file_types;
        $regions = RegionService::getRegions();

        return [
            'registration_file_types' => $registration_file_types,
            'regions' => $regions
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

    public function storeMain(Request $request)
    {
        $validatedRequest = $this->validate($request, [
            'token' => 'required|string',
            'user_name' => 'required|string',
            'user_phone' => 'required|digits:12',
            'name' => 'required|string',
            'categories' => 'required|array',
            'stores_count' => 'required|integer',
            'merchant_users_count' => 'required|integer',
            'approximate_sales' => 'required|integer',
            'information' => 'nullable|string',
            'region' => 'required|string',
        ]);
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

    public function storeDocuments(Request $request)
    {
        $validatedRequest = $this->validate($request, [
            'token' => 'required|string',
            'director_name' => 'required|max:255',
            'legal_name' => 'required|string',
            'phone' => 'required|digits:12',
            'vat_number' => 'required|digits:12',
            'mfo' => 'required|digits:5',
            'tin' => 'required|digits:9',
            'oked' => 'required|digits:5',
            'bank_account' => 'required|digits:20',
            'bank_name' => 'required|max:255',
            'address' => 'required|string'
        ]);

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

    public function upload(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'file_type' => [
                'required',
                'string',
                'max:100',
                Rule::in(array_keys(File::$registration_file_types))
            ],
            'file' => 'required|file|mimes:jpeg,bmp,png,svg,jpg,pdf,xlsx,xls',
        ]);

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
}
