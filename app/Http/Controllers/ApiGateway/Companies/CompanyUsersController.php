<?php


namespace App\Http\Controllers\ApiGateway\Companies;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanyUsersController extends ApiBaseController
{

    public function index(Request $request)
    {
        $azo_merchant_accesses = CompanyUser::query()
            ->with(['azo_merchant_access' => function(Builder $query) {
                $query->with(['merchant', 'store']);
            }])
            ->filterRequest($request)
            ->orderRequest($request);

        return $azo_merchant_accesses->paginate($request->query('per_page') ?? 15);
    }

}
