<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajementEvent\Http\Controllers\ManajementEventController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('manajementevents', ManajementEventController::class)->names('manajementevent');
});
