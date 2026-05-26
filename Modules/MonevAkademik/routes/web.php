<?php

use Illuminate\Support\Facades\Route;
use Modules\MonevAkademik\app\Http\Controllers\ExamProposalController;
use Modules\MonevAkademik\app\Http\Controllers\ExamReviewController;
use Modules\MonevAkademik\app\Http\Controllers\BankSoalController;

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

    // UBAH DUA BARIS INI (Hapus monevakademik.-nya)
    Route::get('/tashih/print/{uuid}', [ExamProposalController::class, 'print'])->name('tashih.print');
    Route::post('/tashih/comment', [ExamProposalController::class, 'storeComment'])->name('tashih.comment');
    Route::get('tashih/api/units', [BankSoalController::class, 'getUnits']);
    Route::get('tashih/api/approved-courses', [BankSoalController::class, 'getApprovedCourses']);
    Route::get('tashih/api/bank-soal/{course_id}', [BankSoalController::class, 'getApiQuestions']);
    // Ganti yang lama jadi ini (pastiin pake getApiQuestions)
    // Route::get('/bank-soal/api/questions/{course_id}', [BankSoalController::class, 'getApiQuestions']);
    // API buat narik daftar Paket Soal (Proposal) yang udah di-ACC
    Route::get('/bank-soal/api/proposals/{course_id}', [BankSoalController::class, 'getApprovedProposals']);
    // Route::get('/monev-akademik/bank-soal/api/periods', [BankSoalController::class, 'getPeriods']);
    Route::get('/bank-soal/api/periods', [BankSoalController::class, 'getPeriods']);
    // Route::get('/bank-soal/api/proposals/{course_id}', [BankSoalController::class, 'getApprovedProposals']);
});