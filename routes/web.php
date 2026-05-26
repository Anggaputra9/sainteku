<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // <-- Pastikan ini ada
use App\Http\Controllers\MenuManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Settings\AiSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

// ==================================================
// PUBLIC ROUTES
// ==================================================

// Halaman Landing
// Route::get('/', function () {
//     return view('landing');
// })->name('home');

Route::get('/', [NewsController::class, 'index']);

// Login routes
Route::get('login', function () {
    return redirect('/');
})->name('login');

Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Reset Password Routes
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Email Verification (token public, klik dari email user)
Route::get('email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

// Check Auth (untuk AJAX)
Route::get('/check-auth', [LoginController::class, 'checkAuth'])->name('check.auth');

// Language Switcher
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

// ==================================================
// PROTECTED ROUTES
// ==================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard - Mengarah ke DashboardController yang udah kita buat
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Email Verification (untuk user yang sudah login, kirim ulang link)
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    /*
     |---------------------------------------------------------------
     | PENGATURAN APLIKASI (/settings/*)
     |---------------------------------------------------------------
     | Hanya untuk administrator. URL pakai prefix /settings biar
     | scalable untuk subkonfigurasi lain (email, AI, menu, dsb).
     |
     | Konvensi route name: settings.<sub>.<action>
     |   /settings/email -> settings.email.index
     |   /settings/menu  -> settings.menu.index
     */
    Route::prefix('settings')->name('settings.')->group(function () {

        // Pengaturan Email (mailer multi-provider)
        Route::resource('email', EmailSettingController::class)
            ->except(['show', 'create', 'edit'])
            ->parameters(['email' => 'emailSetting'])
            ->names('email');

        Route::post('email/{emailSetting}/set-default', [EmailSettingController::class, 'setDefault'])
            ->name('email.set-default');
        Route::post('email/{emailSetting}/test', [EmailSettingController::class, 'test'])
            ->name('email.test');

        // Pengaturan AI (multi-provider AI)
        Route::resource('ai', AiSettingController::class)
            ->except(['show', 'create', 'edit'])
            ->parameters(['ai' => 'aiSetting'])
            ->names('ai');

        Route::post('ai/{aiSetting}/set-default', [AiSettingController::class, 'setDefault'])
            ->name('ai.set-default');
        Route::post('ai/{aiSetting}/test', [AiSettingController::class, 'test'])
            ->name('ai.test');
        Route::post('ai/{aiSetting}/reset-usage', [AiSettingController::class, 'resetUsage'])
            ->name('ai.reset-usage');

        // Manajemen Menu Aplikasi
        Route::resource('menu', MenuManagementController::class)
            ->except(['show', 'create', 'edit'])
            ->names('menu');
    });

});

// ==================================================
// AUTH ROUTES BAWAAN BREEZE/FORTIFY
// ==================================================
require __DIR__ . '/auth.php';

// Route Modul Master Data, Monev, Infrastruktur, dll tetap ada di file route masing-masing (seperti module.php / di luar web.php)