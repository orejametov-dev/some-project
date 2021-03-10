<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;

class MonitoringController extends Controller
{
    public function general()
    {
        //check database
        Merchant::query()->limit(1)->get();

        return response()->json(['status' => 'OK']);
    }
}
