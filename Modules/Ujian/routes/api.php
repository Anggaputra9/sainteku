<?php

use Illuminate\Support\Facades\Route;
use Modules\Ujian\Http\Controllers\UjianController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ujians', UjianController::class)->names('ujian');
});
