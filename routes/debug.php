<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/debug-login', function () {
    // Check if user exists
    $user = User::where('email', 'test@example.com')->orWhere('id', 'u0001')->first();
    
    return [
        'user' => $user,
        'user_exists' => $user ? true : false,
        'all_users' => User::all(),
        'test_password_hash' => $user ? Hash::check('password', $user->password) : 'N/A',
    ];
});
