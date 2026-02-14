<?php

use Illuminate\Support\Facades\Route;
use Modules\Pelaporan\Http\Controllers\PelaporanController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pelaporans', PelaporanController::class)->names('pelaporan');
});
