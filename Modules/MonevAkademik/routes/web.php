<?php

use Illuminate\Support\Facades\Route;
use Modules\MonevAkademik\Http\Controllers\MonevAkademikController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('monevakademiks', MonevAkademikController::class)->names('monevakademik');
});
