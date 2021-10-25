<?php


namespace App\Http\Controllers\ApiMerchantGateway\App;


use App\Http\Controllers\Controller;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Companies\Models\Module;
use App\Services\User;
use Illuminate\Support\Facades\Cache;

class AppController extends Controller
{
    public function index()
    {
        $modules = Module::allCached();

        $company_user_modules = Cache::tags('company')->remember('company_user_' . app(User::class)->id, 24 * 60, function () {
            $company_user = CompanyUser::query()
                ->byUser(app(User::class)->id)->first();

            $company_user_modules = [];

            $company_user_modules['merchant_module'] = $company_user->company->merchant()->active()->exists();
            $company_user_modules['alifshop_module'] = $company_user->company->alifshop_merchant()->active()->exists();

            return $company_user_modules;
        });


        return [
            'modules' => $modules,
            'company_user_modules' => $company_user_modules
        ];
    }
}
