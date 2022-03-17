<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Merchant;

class MonitoringController extends Controller
{
    public function general()
    {
//        Log::channel('urgent')->debug('start_service', [
//            'time' => LARAVEL_START
//        ]);
//        Log::channel('urgent')->debug('start_controller', [
//            'time' => microtime(true)
//        ]);

        //check database
        Merchant::query()->limit(1)->get();

//        Log::channel('urgent')->debug('end_controller', [
//            'time' => microtime(true)
//        ]);
        return response()->json(['status' => 'OK']);
    }
}
