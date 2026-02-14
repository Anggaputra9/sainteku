<?php

use Illuminate\Support\Facades\Route;
use Modules\PengaduanMahasiswa\Http\Controllers\PengaduanMahasiswaController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pengaduanmahasiswas', PengaduanMahasiswaController::class)->names('pengaduanmahasiswa');
});
