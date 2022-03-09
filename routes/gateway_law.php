<?php

use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCasesController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
    });
