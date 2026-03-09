<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRepository\app\Http\Controllers\DocumentRepositoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// UBAH: prefix biarkan kecil, tetapi 'name' diubah menjadi DocumentRepository. (dengan D dan R besar)
Route::middleware(['web', 'auth'])
    ->prefix('documentrepository')
    ->name('DocumentRepository.') // <-- Bagian ini yang saya ubah
    ->group(function () {
        Route::get('/', [DocumentRepositoryController::class, 'index'])->name('index');
        // Route::get('/create', [DocumentRepositoryController::class, 'create'])->name('create');
        Route::post('/', [DocumentRepositoryController::class, 'store'])->name('store');
        Route::get('/{id}/download', [DocumentRepositoryController::class, 'download'])->name('download');

            // Route untuk dashboard khusus Document Repository
        Route::get('/dashboard', [DocumentRepositoryController::class, 'dashboard'])->name('dashboard.index');
        Route::post('/{id}/review', [DocumentRepositoryController::class, 'review'])->name('review');
        Route::post('/{id}/revise', [DocumentRepositoryController::class, 'revise'])->name('revise');
});