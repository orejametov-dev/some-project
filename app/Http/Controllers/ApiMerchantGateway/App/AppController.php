<?php


namespace App\Http\Controllers\ApiMerchantGateway\App;


use App\Http\Controllers\Controller;
use App\Modules\Companies\Models\Module;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::allCached();

        return [
            'modules' => $modules
        ];
    }
}
