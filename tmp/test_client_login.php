<?php

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'omargad@gmail.com')->first();
if (!$user) {
    die("User not found!\n");
}

Auth::login($user);

// Simulate dashboard route retrieval
$authService = app(\App\Services\AuthService::class);
$route = $authService->getDashboardRoute($user);

echo "Redirection Route: " . $route . "\n";

// Check subscription through middleware logic simulation
$client = $user->client;
if (!$client) {
    echo "NO CLIENT LINKED!\n";
} else {
    echo "Client: " . $client->name . "\n";
    echo "Active: " . ($client->isActive() ? 'YES' : 'NO') . "\n";
}
