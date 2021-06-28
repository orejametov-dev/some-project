<?php

use App\Http\Controllers\ApiCallsGateway\ProblemCases\ProblemCasesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/', [ProblemCasesController::class, 'index']);
    });
