<?php

use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCasesController;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
    });
