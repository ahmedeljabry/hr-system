<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = ['Employee Name', 'Email Address', 'Password', 'Position', 'National ID / Residency Number', 'Phone Number', 'Emergency Phone', 'Bank IBAN', 'Basic Salary', 'Housing Allowance', 'Transportation Allowance', 'Other Allowances', 'Date of Birth', 'Hire Date'];
$sheet->fromArray($headers, NULL, 'A1');

$faker = Faker\Factory::create('ar_SA');

$data = [];
for($i=0; $i<15; $i++) {
    $data[] = [
        $faker->name,
        $faker->unique()->safeEmail,
        'password123',
        $faker->jobTitle,
        $faker->numerify('##########'),
        $faker->phoneNumber,
        $faker->phoneNumber,
        'SA' . $faker->numerify('######################'),
        rand(3000, 10000),
        rand(500, 2000),
        rand(200, 1000),
        rand(0, 500),
        $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
        $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d')
    ];
}

$sheet->fromArray($data, NULL, 'A2');

$writer = new Xlsx($spreadsheet);
$writer->save('dummy_employees.xlsx');
echo "Saved dummy_employees.xlsx\n";
