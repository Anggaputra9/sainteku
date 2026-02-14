<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenAchievement\Http\Controllers\ManajemenAchievementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('manajemenachievements', ManajemenAchievementController::class)->names('manajemenachievement');
});
