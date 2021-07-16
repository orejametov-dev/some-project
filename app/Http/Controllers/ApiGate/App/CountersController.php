<?php


namespace App\Http\Controllers\ApiGate\App;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;

class CountersController extends Controller
{
    public function index()
    {
        $merchants_count = Merchant::query()->count();
        $stores_count = Store::query()->count();

        return compact('merchants_count', 'stores_count');
    }
}
