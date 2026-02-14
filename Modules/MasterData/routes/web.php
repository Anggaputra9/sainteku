<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('masterdatas', MasterDataController::class)->names('masterdata');
});
