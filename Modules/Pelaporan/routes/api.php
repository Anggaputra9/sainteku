<?php

use Illuminate\Support\Facades\Route;
use Modules\Pelaporan\Http\Controllers\PelaporanController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pelaporans', PelaporanController::class)->names('pelaporan');
});
