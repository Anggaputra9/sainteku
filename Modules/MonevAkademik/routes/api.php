<?php

use Illuminate\Support\Facades\Route;
use Modules\MonevAkademik\Http\Controllers\MonevAkademikController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('monevakademiks', MonevAkademikController::class)->names('monevakademik');
});
