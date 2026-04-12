<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenAchievement\app\Http\Controllers\AchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\DosenAchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use Modules\ManajemenAchievement\app\Http\Controllers\Admin\DosenController as AdminDosenController;
use Modules\ManajemenAchievement\app\Http\Controllers\PortfolioController;

// ==================================================
// 1. PRESTASI MAHASISWA (mahasiswa + admin super)
// ==================================================
Route::middleware(['auth', 'role:mahasiswa,admin'])->prefix('prestasi-mahasiswa')->name('student.achievements.')->group(function () {
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
// 2. REPOSITORY PRESTASI DOSEN (dosen + admin super)
// ==================================================
Route::middleware(['auth', 'role:dosen,admin'])->prefix('repositori-dosen')->name('dosen.repository.')->group(function () {
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
// 3. PORTOFOLIO (semua user yang login)
// ==================================================
Route::middleware(['auth'])->prefix('portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('index');
    Route::get('/{userId}', [PortfolioController::class, 'show'])->name('show');
});

// ==================================================
// 4. ADMIN PRESTASI MAHASISWA (admin unit + admin super)
// ==================================================
Route::middleware(['auth', 'role:admin_unit,admin'])->prefix('admin/prestasi')->name('admin.achievements.')->group(function () {
    Route::get('/', [AdminAchievementController::class, 'index'])->name('index');
    Route::get('/pending', [AdminAchievementController::class, 'pending'])->name('pending');
    Route::get('/{id}', [AdminAchievementController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminAchievementController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminAchievementController::class, 'reject'])->name('reject');
});

// ==================================================
// 5. ADMIN PRESTASI DOSEN (admin unit + admin super)
// ==================================================
Route::middleware(['auth', 'role:admin_unit,admin'])->prefix('admin/prestasi-dosen')->name('admin.dosen.')->group(function () {
    Route::get('/', [AdminDosenController::class, 'index'])->name('index');
    Route::get('/pending', [AdminDosenController::class, 'pending'])->name('pending');
    Route::get('/{id}', [AdminDosenController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminDosenController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminDosenController::class, 'reject'])->name('reject');
});

// HAPUS 'role:mahasiswa,admin' SEMENTARA
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