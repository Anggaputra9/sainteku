<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Test creating a user
    $user = User::create([
        'id' => 'test_user_' . time(),
        'name' => 'Test User ' . time(),
        'email' => 'test_' . time() . '@example.com',
        'password' => Hash::make('password123'),
        'is_active' => true,
    ]);
    
    echo "✓ User created successfully: {$user->id}\n";
    
    // Test assigning roles
    foreach ([1, 2] as $roleId) {
        DB::table('trx_user_role')->insert([
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);
    }
    
    echo "✓ Roles assigned successfully\n";
    
    // Verify
    $userRoles = DB::table('trx_user_role')->where('user_id', $user->id)->get();
    echo "✓ User now has " . $userRoles->count() . " roles\n";
    
    echo "\n✓ All tests passed! Form should work.\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
