<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// ==================================================
// PUBLIC ROUTES
// ==================================================

// Halaman Landing
Route::get('/', function () {
    return view('landing');
})->name('home');

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

// Check Auth (untuk AJAX)
Route::get('/check-auth', [LoginController::class, 'checkAuth'])->name('check.auth');

// ==================================================
// PROTECTED ROUTES
// ==================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard', ['title' => 'Sainteku | Dashboard']);
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================================================
// AUTH ROUTES BAWAAN
// ==================================================
require __DIR__ . '/auth.php';

// ==================================================
// TEST ENDPOINTS (HAPUS NANTI)
// ==================================================

Route::get('/test-login-now', function () {
    $user = User::where('email', 'Syahputranabil521@gmail.com')->first();
    if (!$user) return 'User tidak ditemukan';
    Auth::login($user);
    return 'Login berhasil! <a href="/dashboard">Go to Dashboard</a>';
});

// ==================================================
// STATIC PAGES
// ==================================================

Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');

Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

// Route::get('/force-logout', function () {
   // Auth::logout();
    // session()->flush(); // Hapus semua session
    // session()->regenerate();
    // return redirect('/');
// });

// Route::get('/cek-status', function () {
   // return [
     //   'auth_check' => auth()->check(),
     //   'session_id' => session()->getId(),
     //   'session_data' => session()->all()
   // ];
// });