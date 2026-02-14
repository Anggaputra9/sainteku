<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajementInfrastruktur\Http\Controllers\ManajementInfrastrukturController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('manajementinfrastrukturs', ManajementInfrastrukturController::class)->names('manajementinfrastruktur');
});
