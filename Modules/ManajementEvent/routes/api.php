<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajementEvent\Http\Controllers\ManajementEventController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('manajementevents', ManajementEventController::class)->names('manajementevent');
});
