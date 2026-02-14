<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajementInfrastruktur\Http\Controllers\ManajementInfrastrukturController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('manajementinfrastrukturs', ManajementInfrastrukturController::class)->names('manajementinfrastruktur');
});
