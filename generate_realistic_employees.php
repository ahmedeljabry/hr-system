<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = [
    "الاسم (عربي)", "Name (English)", "Email", "Password", "Position", 
    "ID/Iqama Number", "Phone", "Emergency Phone", "IBAN", "Basic Salary", 
    "Housing", "Transportation", "Other", "DOB", "Hire Date", "Gender", 
    "Leave Days", "Nationality", "Residency Number", "Residency Start", "Residency End"
];

$columnIdx = 1;
foreach ($headers as $header) {
    $sheet->setCellValueByColumnAndRow($columnIdx, 1, $header);
    $columnIdx++;
}

// Data generation arrays
$maleFirstNames = [
    ['ar' => 'أحمد', 'en' => 'Ahmed'], ['ar' => 'محمد', 'en' => 'Mohammed'], ['ar' => 'خالد', 'en' => 'Khaled'],
    ['ar' => 'عمر', 'en' => 'Omar'], ['ar' => 'علي', 'en' => 'Ali'], ['ar' => 'سعيد', 'en' => 'Saeed'],
    ['ar' => 'فيصل', 'en' => 'Faisal'], ['ar' => 'فهد', 'en' => 'Fahad'], ['ar' => 'عبدالله', 'en' => 'Abdullah'],
    ['ar' => 'سلطان', 'en' => 'Sultan'], ['ar' => 'نايف', 'en' => 'Naif'], ['ar' => 'يوسف', 'en' => 'Youssef']
];

$femaleFirstNames = [
    ['ar' => 'سارة', 'en' => 'Sarah'], ['ar' => 'نورة', 'en' => 'Noura'], ['ar' => 'فاطمة', 'en' => 'Fatima'],
    ['ar' => 'ليلى', 'en' => 'Layla'], ['ar' => 'ريم', 'en' => 'Reem'], ['ar' => 'مريم', 'en' => 'Maryam'],
    ['ar' => 'العنود', 'en' => 'Alanoud'], ['ar' => 'جواهر', 'en' => 'Jawahir'], ['ar' => 'أمل', 'en' => 'Amal']
];

$lastNames = [
    ['ar' => 'العتيبي', 'en' => 'Al-Otaibi'], ['ar' => 'القحطاني', 'en' => 'Al-Qahtani'], ['ar' => 'الزهراني', 'en' => 'Al-Zahrani'],
    ['ar' => 'الشمري', 'en' => 'Al-Shammary'], ['ar' => 'الحربي', 'en' => 'Al-Harbi'], ['ar' => 'المطيري', 'en' => 'Al-Mutairi'],
    ['ar' => 'الغامدي', 'en' => 'Al-Ghamdi'], ['ar' => 'الدوسري', 'en' => 'Al-Dossary'], ['ar' => 'العنزي', 'en' => 'Al-Anazi']
];

$nationalities = ['Saudi', 'Egyptian', 'Indian', 'Pakistani', 'Yemeni', 'Sudanese', 'Jordanian'];
$positions = ['General Manager', 'Senior Developer', 'Accountant', 'Technical Support', 'HR Manager', 'Compliance Officer', 'Sales Executive', 'Data Analyst'];

for ($i = 1; $i <= 100; $i++) {
    $row = $i + 1;
    $gender = (rand(1, 10) > 8) ? 'female' : 'male';
    $firstName = ($gender === 'female') ? $femaleFirstNames[array_rand($femaleFirstNames)] : $maleFirstNames[array_rand($maleFirstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    
    $fullNameAr = $firstName['ar'] . ' ' . $lastName['ar'];
    $fullNameEn = $firstName['en'] . ' ' . $lastName['en'];
    $email = strtolower($firstName['en']) . '.' . strtolower(str_replace([' ', '-'], '', $lastName['en'])) . $i . '@example.com';

    $isSaudi = (rand(1, 10) > 6);
    $nationality = $isSaudi ? 'Saudi' : $nationalities[array_rand($nationalities)];
    
    $id = ($isSaudi ? '1' : '2') . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    
    $data = [
        $fullNameAr,
        $fullNameEn,
        $email,
        "pass12345",
        $positions[array_rand($positions)],
        $id,
        "05" . rand(10000000, 99999999),
        "05" . rand(10000000, 99999999),
        "SA" . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT) . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT) . str_pad(rand(1000, 9999), 6, '0', STR_PAD_LEFT),
        rand(5000, 18000), // Basic
        rand(1500, 4500), // Housing
        rand(500, 1500), // Transport
        rand(0, 500), // Other
        date('Y-m-d', strtotime('-' . rand(22, 55) . ' years')), // DOB
        date('Y-m-d', strtotime('-' . rand(1, 12) . ' years')), // Hire
        $gender,
        rand(21, 30), // Leave
        $nationality,
        !$isSaudi ? $id : "", // Residency Num (same as ID for expat)
        !$isSaudi ? date('Y-m-d', strtotime('-' . rand(1, 10) . ' months')) : "", // Start
        !$isSaudi ? date('Y-m-d', strtotime('+' . rand(1, 11) . ' months')) : "", // End
    ];

    $colIdx = 1;
    foreach ($data as $value) {
        $sheet->setCellValueByColumnAndRow($colIdx, $row, $value);
        $colIdx++;
    }
}

$writer = new Xlsx($spreadsheet);
$fileName = 'employees_realistic_100.xlsx';
$writer->save($fileName);

echo "File created: " . $fileName . PHP_EOL;
