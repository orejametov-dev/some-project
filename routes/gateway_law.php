<?php

use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCaseTagsController;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
    });
