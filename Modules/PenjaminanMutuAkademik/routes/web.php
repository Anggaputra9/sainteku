<?php

use Illuminate\Support\Facades\Route;
use Modules\PenjaminanMutuAkademik\Http\Controllers\PenjaminanMutuAkademikController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('penjaminanmutuakademiks', PenjaminanMutuAkademikController::class)->names('penjaminanmutuakademik');
});
