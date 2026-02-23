<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;

echo "Total roles: " . Role::count() . "\n";
echo "Roles list:\n";
foreach (Role::all() as $role) {
    echo "- ID: {$role->id}, Name: {$role->role_name}\n";
}
