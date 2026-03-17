<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajementInfrastruktur\Http\Controllers\ManajementInfrastrukturController;

Route::middleware(['auth'])->prefix('manajementinfrastruktur')->name('manajementinfrastruktur.')->group(function () {
    
    // 1. Sub-menu Dashboard
    Route::get('/dashboard', [ManajementInfrastrukturController::class, 'index'])->name('dashboard');

    // 2. Sub-menu Pengajuan Peminjaman (Untuk Mahasiswa/Dosen)
    Route::get('/pengajuan', [ManajementInfrastrukturController::class, 'pengajuanIndex'])->name('pengajuan.index');
    Route::post('/pengajuan', [ManajementInfrastrukturController::class, 'pengajuanStore'])->name('pengajuan.store');

    // 3. Sub-menu ACC / Persetujuan (Untuk Admin/Fakultas)
    Route::get('/persetujuan', [ManajementInfrastrukturController::class, 'persetujuanIndex'])->name('persetujuan.index');
    Route::put('/persetujuan/{id}', [ManajementInfrastrukturController::class, 'persetujuanUpdate'])->name('persetujuan.update');

});