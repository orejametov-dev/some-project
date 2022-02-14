<?php

use Illuminate\Support\Facades\Route;

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
    $client =  Http::baseUrl('alif-service-prm-nginx')
        ->withHeaders([
            'Accept' => 'application/json',
            'Access-Token' => 123,
            'Content-Type' => 'application/json',
        ]);

    $client->get('/');
});

Route::get('monitoring/status/health', [\App\Http\Controllers\Monitoring\MonitoringController::class, 'general']);
