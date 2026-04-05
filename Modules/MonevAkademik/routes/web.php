<?php

use Illuminate\Support\Facades\Route;
use Modules\MonevAkademik\App\Http\Controllers\ExamProposalController;
use Modules\MonevAkademik\App\Http\Controllers\ExamReviewController;
use Modules\MonevAkademik\App\Http\Controllers\BankSoalController;

// KUNCI: Tambahkan 'role' di sini (atau \App\Http\Middleware\RoleMiddleware::class jika belum di-alias)
Route::middleware(['web', 'auth', 'role'])->prefix('monev-akademik')->name('monevakademik.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | TASHIH SOAL (Pengajuan & Kelola)
    |--------------------------------------------------------------------------
    */
    // Halaman Utama (Masuk ke tabel mst_menu)
    Route::get('/tashih', [ExamProposalController::class, 'index'])->name('tashih.index');
    
    // Alur Create & Store
    Route::get('/tashih/create/{course_id}', [ExamProposalController::class, 'create'])->name('tashih.create');
    Route::post('/tashih/store', [ExamProposalController::class, 'store'])->name('tashih.store');
    
    // Alur Detail, Edit, Update, Delete
    Route::get('/tashih/detail/{uuid}', [ExamProposalController::class, 'show'])->name('tashih.show');
    Route::get('/tashih/edit/{uuid}', [ExamProposalController::class, 'edit'])->name('tashih.edit');
    Route::put('/tashih/update/{uuid}', [ExamProposalController::class, 'update'])->name('tashih.update');
    Route::delete('/tashih/destroy/{uuid}', [ExamProposalController::class, 'destroy'])->name('tashih.destroy');

    /*
    |--------------------------------------------------------------------------
    | AKSI REVIEWER (Kaprodi)
    |--------------------------------------------------------------------------
    */
    Route::post('/tashih/{uuid}/approve', [ExamReviewController::class, 'approve'])->name('tashih.approve');
    Route::post('/tashih/{uuid}/revise', [ExamReviewController::class, 'revise'])->name('tashih.revise');

    /*
    |--------------------------------------------------------------------------
    | BANK SOAL REPOSITORY
    |--------------------------------------------------------------------------
    */
    // Halaman Utama Bank Soal (Masuk ke tabel mst_menu)
    Route::get('/bank-soal', [BankSoalController::class, 'index'])->name('banksoal.index');
    
    // API untuk ditarik ke dalam Modal Create/Edit
    Route::get('/tashih/api/bank-soal/{course_id}', [BankSoalController::class, 'getApiQuestions'])->name('tashih.api.banksoal');
    Route::get('/tashih/print/{uuid}', [ExamProposalController::class, 'print'])->name('monevakademik.tashih.print');

});