<?php

use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCaseTagsController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
    });
