<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return view('landing');
});

// Auth routes (Tailadmin UI - Modal on landing page)
Route::get('login', function () {
    return redirect('/');  // Redirect to landing page (modal ada di sana)
})->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Debug endpoints
Route::get('/debug/auth', function () {
    return response()->json([
        'authenticated' => \Illuminate\Support\Facades\Auth::check(),
        'user_id' => \Illuminate\Support\Facades\Auth::id(),
        'user' => \Illuminate\Support\Facades\Auth::user(),
        'session_id' => session()->getId(),
    ]);
});

Route::get('/debug/masterdata-test', function () {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    return response()->json([
        'message' => 'Authenticated! Can access masterdata.',
        'user_id' => \Illuminate\Support\Facades\Auth::id(),
    ]);
})->middleware('auth');

Route::get('/test-page', function () {
    return view('test-page');
});

Route::post('/test-login', function (\Illuminate\Http\Request $request) {
    $cred = $request->validate(['credential' => 'required', 'password' => 'required']);
    
    $user = \App\Models\User::where('email', $cred['credential'])
                              ->orWhere('id', $cred['credential'])
                              ->first();
    
    if (!$user || !\Illuminate\Support\Facades\Hash::check($cred['password'], $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
    
    // Try to login
    \Illuminate\Support\Facades\Auth::login($user, true);
    
    return response()->json([
        'success' => true,
        'user_id' => $user->id,
        'authenticated' => \Illuminate\Support\Facades\Auth::check(),
        'auth_user' => \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->id : null,
    ]);
});
Route::get('/debug/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'No log file found';
    }
    
    $lines = file($logFile);
    $recentLines = array_slice($lines, -50);
    
    return '<pre>' . htmlspecialchars(implode('', $recentLines)) . '</pre>';
});

Route::get('/debug/sessions-table', function () {
    $sessions = DB::table('sessions')->latest('last_activity')->limit(5)->get();
    $data = [];
    foreach ($sessions as $session) {
        $payload = unserialize(base64_decode($session->payload));
        $data[] = [
            'id' => $session->id,
            'user_id' => $session->user_id,
            'last_activity' => $session->last_activity,
            'payload_keys' => array_keys($payload),
        ];
    }
    return response()->json(['sessions' => $data], 200);
});

Route::get('/test-manual-login', function () {
    // Manually log in a user for testing
    $user = \App\Models\User::find('u0001');
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    
    \Illuminate\Support\Facades\Auth::login($user);
    $sessionId = session()->getId();
    
    \Log::info('RESPONSE_HEADERS_CHECK', [
        'session_id' => $sessionId,
        'auth_check' => \Illuminate\Support\Facades\Auth::check(),
        'auth_id' => \Illuminate\Support\Facades\Auth::id(),
    ]);
    
    $response = response()->json([
        'auth_check_now' => \Illuminate\Support\Facades\Auth::check(),
        'auth_id_now' => \Illuminate\Support\Facades\Auth::id(),
        'session_id' => $sessionId,
    ]);
    
    // This is where cookies should be set
    // Let's log what we see after the framework processes the response
    return $response;
});

Route::get('/debug/check-session/{sessionId}', function ($sessionId) {
    $dbSession = DB::table('sessions')->find($sessionId);
    if (!$dbSession) {
        return response()->json(['error' => 'Session not found'], 404);
    }
    
    $payload = unserialize(base64_decode($dbSession->payload));
    
    return response()->json([
        'session_id' => $sessionId,
        'db_user_id' => $dbSession->user_id,
        'payload_keys' => array_keys($payload),
        'current_auth_check' => \Illuminate\Support\Facades\Auth::check(),
        'current_auth_id' => \Illuminate\Support\Facades\Auth::id(),
        'current_session_id' => session()->getId(),
    ]);
});

Route::get('/debug/verify-session-persistence', function () {
    // This route is accessed AFTER a login redirect
    // It will show whether the session and auth persist
    return response()->json([
        'current_auth_check' => \Illuminate\Support\Facades\Auth::check(),
        'current_auth_id' => \Illuminate\Support\Facades\Auth::id(),
        'current_session_id' => session()->getId(),
        'auth_user' => \Illuminate\Support\Facades\Auth::user() ? [
            'id' => \Illuminate\Support\Facades\Auth::user()->id,
            'email' => \Illuminate\Support\Facades\Auth::user()->email,
        ] : null,
        'request_headers' => [
            'cookies_present' => array_keys(request()->cookies->all()),
        ],
    ]);
});