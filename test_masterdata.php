<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

$request = Illuminate\Http\Request::create('/masterdata/test-users-debug', 'GET');
$response = $kernel->handle($request);

echo "STATUS: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() >= 500) {
    echo $response->getContent();
}
