<?php


namespace App\Http\Controllers\ApiGateway\App;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\CancelReason;
use App\Modules\Merchants\Models\Competitor;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\Request;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Services\MerchantStatus;
use App\Services\ClientTypeRegisterService;
use App\Services\DistrictService;
use App\Services\LegalNameService;
use App\Services\RegionService;

class AppController extends ApiBaseController
{
    public function index()
    {
        $merchant_requests_count = Request::query()->new()->count();
        $merchants_count = Merchant::query()->count();
        $stores_count = Store::query()->count();
        $merchant_request_statuses = Request::statusLists();
        $merchant_statuses = MerchantStatus::get();
        $problem_case_statuses = array_values(ProblemCase::$statuses);
        $problem_case_sources = ProblemCase::$sources;
        $merchant_activity_reasons = ActivityReason::query()->where('type', 'MERCHANT')->get();
        $store_activity_reasons = ActivityReason::query()->where('type', 'STORE')->get();
        $cancel_reasons = CancelReason::query()->get();
        $legal_name_prefixes = LegalNameService::getNamePrefixes();
        $competitors = Competitor::query()->select('id', 'name')->get()->toArray();

        $authUser = $this->user;

        $file_types = File::$file_types;
        $registration_file_types = File::$registration_file_types;

        $regions = RegionService::getRegions();

        $client_type_register = ClientTypeRegisterService::getClientTypeRegister();


        $me = [
            'id' => $authUser->id,
            'name' => $authUser->name,
            'phone' => $authUser->phone,
            'avatar_link' => $authUser->avatar_link
        ];

        return response()->json(compact(
            'merchant_requests_count',
            'merchants_count',
            'stores_count',
            'me',
            'merchant_request_statuses',
            'merchant_statuses',
            'problem_case_statuses',
            'file_types',
            'registration_file_types',
            'regions',
            'problem_case_sources',
            'merchant_activity_reasons',
            'store_activity_reasons',
            'cancel_reasons',
            'client_type_register',
            'legal_name_prefixes',
            'competitors'
        ));
    }

    public function getDistricts(\Illuminate\Http\Request $request)
    {
        if ($request->query('region')) {
            return DistrictService::getDistrictsByRegion($request->query('region'));

        }
        return DistrictService::getDistricts();
    }
}
