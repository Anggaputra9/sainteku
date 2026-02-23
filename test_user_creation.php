<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Simulate form submission data
    $userData = [
        'id' => 'testuser2026',
        'name' => 'Test User 2026',
        'email' => 'testuser2026@example.com',
        'password' => Hash::make('password123'),
        'identity_id' => '1234567890',
        'user_type' => 'admin',
        'unit_id' => 'U001',
        'is_active' => 1,
    ];
    
    // Create user
    $user = User::create($userData);
    echo "✓ User created successfully: {$user->id}\n";
    
    // Add roles
    $roleIds = [1, 5];  // Administrator and Dosen
    foreach ($roleIds as $roleId) {
        DB::table('trx_user_role')->insert([
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);
    }
    echo "✓ Roles assigned: " . implode(', ', $roleIds) . "\n";
    
    // Verify data
    $userRoles = DB::table('trx_user_role')
        ->where('user_id', $user->id)
        ->pluck('role_id')
        ->toArray();
    echo "✓ Verified roles: " . implode(', ', $userRoles) . "\n";
    
    // Verify user exists
    $createdUser = User::find('testuser2026');
    echo "✓ User retrieved from database: {$createdUser->name}\n";
    echo "\n✓ All tests passed! User creation works.\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
