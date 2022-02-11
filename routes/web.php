<?php

use Illuminate\Support\Facades\Route;
use Jenssegers\Mongodb\Schema\Blueprint;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    Schema::connection('mongodb')->create('logs', function (Blueprint $table) {
        $table->id();
    });
});

Route::get('monitoring/status/health', [\App\Http\Controllers\Monitoring\MonitoringController::class, 'general']);
