<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRepository\app\Http\Controllers\DocumentRepositoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('documentrepository')->middleware(['web', 'auth'])->group(function () {
    
    // 1. Route Dashboard Statistik
    Route::get('/dashboard', [DocumentRepositoryController::class, 'dashboard'])->name('DocumentRepository.dashboard.index');
    
    // 2. Route Utama (Daftar & Unggah Dokumen)
    Route::get('/api/data', [DocumentRepositoryController::class, 'getDocumentsData'])->name('DocumentRepository.api.data');
    Route::get('/', [DocumentRepositoryController::class, 'index'])->name('DocumentRepository.index');
    Route::post('/store', [DocumentRepositoryController::class, 'store'])->name('DocumentRepository.store');
    Route::get('/download/{id}', [DocumentRepositoryController::class, 'download'])->name('DocumentRepository.download');

    // 3. Route Tabel Reviewer (Persetujuan)
    Route::get('/review/api/data', [DocumentRepositoryController::class, 'getReviewDocumentsData'])->name('DocumentRepository.review.api.data');
    Route::get('/review', [DocumentRepositoryController::class, 'reviewIndex'])->name('DocumentRepository.review.index');

    // 4. Route Aksi Review & Revisi
    Route::post('/{id}/review', [DocumentRepositoryController::class, 'review'])->name('DocumentRepository.review');
    Route::post('/{id}/revise', [DocumentRepositoryController::class, 'revise'])->name('DocumentRepository.revise');

});