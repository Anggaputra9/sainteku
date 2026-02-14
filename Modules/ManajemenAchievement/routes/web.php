<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenAchievement\Http\Controllers\ManajemenAchievementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('manajemenachievements', ManajemenAchievementController::class)->names('manajemenachievement');
});
