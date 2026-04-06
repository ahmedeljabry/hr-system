<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payslip;

$payslips = Payslip::with('employee')->get();
$count = 0;

foreach ($payslips as $payslip) {
    if ($payslip->employee) {
        $payslip->update([
            'housing_allowance' => $payslip->employee->housing_allowance,
            'transportation_allowance' => $payslip->employee->transportation_allowance,
            'other_allowances' => $payslip->employee->other_allowances,
        ]);
        $count++;
    }
}

echo "Backfilled $count payslips.\n";
