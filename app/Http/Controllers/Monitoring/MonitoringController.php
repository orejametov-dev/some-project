<?php

namespace App\Http\Controllers\Monitoring;

use App\Modules\Auth\Models\User;
use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function general()
    {
        //check database
        User::query()->limit(1)->get();

        return response()->json(['status' => 'OK']);
    }
}
