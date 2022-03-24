<?php

declare(strict_types=1);

use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiLawGateway\ProblemCases\ProblemCaseTagsController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}/attach-tags', [ProblemCasesController::class, 'attachTags']);
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
    });
