<?php


namespace App\Http\Controllers\ApiGateway\Companies;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Companies\Models\CompanyUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanyUsersController extends ApiBaseController
{
    public function index(Request $request)
    {
        $company_users = CompanyUser::query()
            ->with(['azo_merchant_access' => function ($query) {
                $query->with(['merchant', 'store']);
            }])->filterRequest($request);


        return $company_users->paginate($request->query('per_page') ?? 15);
    }
}
