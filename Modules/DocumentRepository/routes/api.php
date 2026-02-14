<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRepository\Http\Controllers\DocumentRepositoryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('documentrepositories', DocumentRepositoryController::class)->names('documentrepository');
});
