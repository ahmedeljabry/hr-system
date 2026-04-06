<?php

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$clients = App\Models\Client::with('user')->get();

echo "ID\t\tName\t\t\tEmail\t\t\tStatus\t\tUserID\n";
echo "------------------------------------------------------------------------------------------\n";
foreach ($clients as $c) {
    $email = $c->user->email ?? 'N/A';
    $uid = $c->user->id ?? 'N/A';
    echo "{$c->id}\t\t" . str_pad($c->name, 20) . "\t" . str_pad($email, 30) . "\t{$c->status}\t\t{$uid}\n";
}
