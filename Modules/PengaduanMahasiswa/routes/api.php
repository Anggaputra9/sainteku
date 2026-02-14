<?php

use Illuminate\Support\Facades\Route;
use Modules\PengaduanMahasiswa\Http\Controllers\PengaduanMahasiswaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pengaduanmahasiswas', PengaduanMahasiswaController::class)->names('pengaduanmahasiswa');
});
