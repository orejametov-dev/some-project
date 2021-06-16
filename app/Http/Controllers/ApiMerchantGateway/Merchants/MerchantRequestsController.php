<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Services\RegionService;
use Illuminate\Http\Request;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use Illuminate\Support\Str;
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

    public function show($id) {

        if(preg_match('/^\d+$/', $id)) {
            $merchant_request = MerchantRequest::with('files')->find($id);
        } else {
            $merchant_request = MerchantRequest::with('files')->where('token', $id)->first();
        }

        return $merchant_request;
    }

    public function store(Request $request)
    {
        $validatedRequest = $this->validate($request, [
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
        $merchant_request = new MerchantRequest($validatedRequest);
        $merchant_request->token = Str::uuid();
        $merchant_request->setStatusNew();
        $merchant_request->save();

        return $merchant_request;
    }

    public function update($id, Request $request)
    {
        $validatedRequest = $this->validate($request, [
            'user_name' => 'required|string',
            'user_phone' => 'required|digits:12',
            'name' => 'required|string',
            'categories' => 'required|array',
            'stores_count' => 'required|integer',
            'merchant_users_count' => 'required|integer',
            'approximate_sales' => 'required|integer',
            'information' => 'nullable|string',
            'region' => 'required|string',

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

        $merchant_request = MerchantRequest::findOrFail($id);
        $merchant_request->fill($validatedRequest);
        $merchant_request->save();

        return $merchant_request;
    }

    public function upload($id, Request $request) {

        $this->validate($request, [
            'file_type' => [
                'required',
                'string',
                'max:100',
                Rule::in(array_keys(File::$registration_file_types))
            ],
            'file' => 'required|file|mimes:jpeg,bmp,png,svg,jpg,pdf,xlsx,xls',
        ]);

        /** @var Merchant $merchant */
        $merchant_request = MerchantRequest::query()->findOrFail($id);
        $merchant_request_file = $merchant_request->uploadFile($request->file('file'), $request->input('file_type'));
        return $merchant_request_file;
    }
}
