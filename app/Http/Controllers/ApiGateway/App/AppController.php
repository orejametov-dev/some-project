<?php


namespace App\Http\Controllers\ApiGateway\App;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Services\ProblemCaseStatus;
use App\Modules\Merchants\Services\RequestStatus;
use App\Modules\Merchants\Traits\ProblemCaseStatuses;
use App\Services\RegionService;

class AppController extends ApiBaseController
{
    public function index()
    {
        $merchant_requests_count = Request::query()->new()->count();
        $merchants_count = Merchant::query()->count();
        $stores_count = Store::query()->count();
        $merchant_request_statuses = RequestStatus::statusLists();
        $problem_case_statuses = ProblemCaseStatus::statusLists();

        $authUser = $this->user;

        $file_types = File::$file_types;

        $regions = RegionService::getRegions();

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
            'problem_case_statuses',
            'file_types',
            'regions'
        ));
    }
}
