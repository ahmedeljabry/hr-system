<?php

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'omargad@gmail.com')->first();
if ($user) {
    $user->update(['password' => Hash::make('password123')]);
    echo "SUCCESS: Password for omargad@gmail.com has been set to: password123\n";
} else {
    echo "ERROR: User omargad@gmail.com not found.\n";
}
