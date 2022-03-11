<?php

declare(strict_types=1);

use App\Http\Controllers\ApiCallsGateway\Comments\CommentsController;
use App\Http\Controllers\ApiCallsGateway\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiCallsGateway\ProblemCases\ProblemCaseTagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('comments')->group(function () {
    Route::get('/', [CommentsController::class, 'index']);
});

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/', [ProblemCasesController::class, 'index']);
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
        Route::get('/statuses', [ProblemCasesController::class, 'getStatusList']);
        Route::get('/{id}', [ProblemCasesController::class, 'show']);
        Route::post('/', [ProblemCasesController::class, 'store']);
    });
