<?php

use Illuminate\Support\Facades\Route;

use Modules\ManajemenAchievement\Http\Controllers\ManajemenAchievementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('manajemenachievements', ManajemenAchievementController::class)->names('manajemenachievement');
});

use Modules\ManajemenAchievement\app\Http\Controllers\AchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\DosenAchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\Admin\DosenController as AdminDosenController;
use Modules\ManajemenAchievement\app\Http\Controllers\PortfolioController;

// ==================================================
// 1. PRESTASI MAHASISWA (untuk mahasiswa input)
// ==================================================
Route::middleware(['auth'])->prefix('prestasi-mahasiswa')->name('student.achievements.')->group(function () {
    Route::get('/', [AchievementController::class, 'index'])->name('index');
    Route::get('/create', [AchievementController::class, 'create'])->name('create');
    Route::post('/', [AchievementController::class, 'store'])->name('store');
    Route::get('/{id}', [AchievementController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [AchievementController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AchievementController::class, 'update'])->name('update');
    Route::delete('/{id}', [AchievementController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/download', [AchievementController::class, 'download'])->name('download');
});

// ==================================================
// 2. REPOSITORY PRESTASI DOSEN (untuk dosen input)
// ==================================================
Route::middleware(['auth'])->prefix('repositori-dosen')->name('dosen.repository.')->group(function () {
    Route::get('/', [DosenAchievementController::class, 'index'])->name('index');
    Route::get('/create', [DosenAchievementController::class, 'create'])->name('create');
    Route::post('/', [DosenAchievementController::class, 'store'])->name('store');
    Route::get('/{id}', [DosenAchievementController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DosenAchievementController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DosenAchievementController::class, 'update'])->name('update');
    Route::delete('/{id}', [DosenAchievementController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/download', [DosenAchievementController::class, 'download'])->name('download');
});

// ==================================================
// 3. PORTOFOLIO (tampilan publik)
// ==================================================
Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('index');
    Route::get('/{userId}', [PortfolioController::class, 'show'])->name('show');
});

// ==================================================
// 4. ADMIN PRESTASI MAHASISWA (approval)
// ==================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin/prestasi')->name('admin.achievements.')->group(function () {
    Route::get('/', [AdminAchievementController::class, 'index'])->name('index');
    Route::get('/pending', [AdminAchievementController::class, 'pending'])->name('pending');
    Route::get('/{id}', [AdminAchievementController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminAchievementController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminAchievementController::class, 'reject'])->name('reject');
});

// ==================================================
// 5. ADMIN PRESTASI DOSEN (approval)
// ==================================================
Route::middleware(['auth', 'role:admin_unit'])->prefix('admin/prestasi-dosen')->name('admin.dosen.')->group(function () {
    Route::get('/', [AdminDosenController::class, 'index'])->name('index');
    Route::get('/pending', [AdminDosenController::class, 'pending'])->name('pending');
    Route::get('/{id}', [AdminDosenController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminDosenController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminDosenController::class, 'reject'])->name('reject');
});

