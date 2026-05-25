<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

$user = Modules\MasterData\app\Models\User::first();
if ($user) {
    auth()->login($user);
}

$request = Illuminate\Http\Request::create('/masterdata/admin/users', 'GET');
$response = $kernel->handle($request);

file_put_contents('debug_users.html', $response->getContent());
echo "Saved to debug_users.html\n";
