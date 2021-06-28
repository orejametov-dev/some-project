<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'index']);
    });
