<?php

// Test auth persistence by checking logs directly from Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Get the application instance
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create test requests
echo "=== Testing Backend Session Authentication ===\n\n";

// Test 1: Check if User model and Auth works
echo "1. Testing User Model...\n";
$user = \App\Models\User::find('u0001');
if ($user) {
    echo "   ✓ Found user u0001: " . $user->email . "\n";
} else {
    echo "   ✗ User u0001 not found\n";
    exit(1);
}

// Test 2: Verify password hashing
echo "\n2. Testing Password Verification...\n";
$password = 'password';
if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
    echo "   ✓ Password verification works\n";
} else {
    echo "   ✗ Password verification failed\n";
    exit(1);
}

// Test 3: Test Auth::login directly
echo "\n3. Testing Auth::login()...\n";
\Illuminate\Support\Facades\Auth::login($user);
if (\Illuminate\Support\Facades\Auth::check()) {
    echo "   ✓ Auth::login() successful\n";
    echo "   ✓ Auth::id() = " . \Illuminate\Support\Facades\Auth::id() . "\n";
} else {
    echo "   ✗ Auth::login() failed\n";
}

// Test 4: Check session data
echo "\n4. Checking Session Storage...\n";
$session = session();
echo "   Session ID: " . $session->getId() . "\n";
echo "   Session data keys: " . implode(', ', array_keys($session->all())) . "\n";

// Test 5: Query sessions table
echo "\n5. Checking sessions table...\n";
$sessionCount = DB::table('sessions')->count();
echo "   Total sessions in DB: " . $sessionCount . "\n";
$latestSession = DB::table('sessions')->latest('last_activity')->first();
if ($latestSession) {
    echo "   Latest session: " . $latestSession->id . "\n";
    echo "   User ID in session: " . $latestSession->user_id . "\n";
}

// Test 6: Check remember_token in database
echo "\n6. Checking User remember_token...\n";
\Illuminate\Support\Facades\Auth::login($user, true);
$userFromDb = \App\Models\User::find('u0001');
if ($userFromDb->remember_token) {
    echo "   ✓ Remember token set: " . substr($userFromDb->remember_token, 0, 10) . "..." . "\n";
} else {
    echo "   ✗ Remember token not set\n";
}

echo "\n=== Backend Test Complete ===\n";
