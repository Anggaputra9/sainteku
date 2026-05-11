<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // <-- Pastikan ini ada
use App\Http\Controllers\Auth\LoginController;
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

// Forgot Password Routes
Route::get('password/forgot', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

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

});

// ==================================================
// AUTH ROUTES BAWAAN BREEZE/FORTIFY
// ==================================================
require __DIR__ . '/auth.php';

// Route Modul Master Data, Monev, Infrastruktur, dll tetap ada di file route masing-masing (seperti module.php / di luar web.php)