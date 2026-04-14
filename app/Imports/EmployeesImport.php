<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EmployeesImport implements ToCollection, SkipsEmptyRows
{
    private int $successCount = 0;
    private array $errors = [];

    public function __construct(private int $clientId)
    {
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function collection(Collection $rows)
    {
        // Skip the first row if it looks like a header
        $firstRow = $rows->first();
        if ($firstRow && isset($firstRow[2]) && !filter_var(trim($firstRow[2]), FILTER_VALIDATE_EMAIL)) {
            $rows = $rows->slice(1);
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1; // 1-based for user-friendly messages
            $rowArray = $row->toArray();

            try {
                $email = isset($rowArray[2]) ? trim($rowArray[2]) : null;

                // Skip rows without a valid email
                if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                $nameAr = trim($rowArray[0] ?? '');
                $nameEn = trim($rowArray[1] ?? ($nameAr ?: 'Employee'));

                if (empty($nameAr) && empty($nameEn)) {
                    $this->errors[] = "Row {$rowNumber}: Name is required (Arabic or English).";
                    continue;
                }

                // Normalize gender
                $genderRaw = mb_strtolower(trim($rowArray[15] ?? 'male'));
                $gender = in_array($genderRaw, ['انثى', 'أنثى', 'female', 'f']) ? 'female' : 'male';

                // 1. Sync User Account
                $user = \App\Models\User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nameEn ?: $nameAr,
                        'password' => $rowArray[3] ?? 'password123',
                        'role' => 'employee',
                        'client_id' => $this->clientId,
                    ]
                );

                $nationalityRaw = trim($rowArray[17] ?? 'Saudi');
                $nationality = $this->normalizeNationality($nationalityRaw);
                $idNumber = trim($rowArray[5] ?? '');
                $residencyNumFromExcel = trim($rowArray[18] ?? '');

                $isSaudi = in_array(strtolower($nationality), ['saudi', 'سعودي']);
                $nationalId = $isSaudi ? $idNumber : null;
                $residencyNum = !$isSaudi ? ($residencyNumFromExcel ?: $idNumber) : null;

                // 2. Sync Employee Data - handle NOT NULL columns with safe defaults
                Employee::updateOrCreate(
                    ['email' => $email, 'client_id' => $this->clientId],
                    [
                        'user_id'                  => $user->id,
                        'name_ar'                  => $nameAr ?: $nameEn,
                        'name_en'                  => $nameEn ?: $nameAr,
                        'gender'                   => $gender,
                        'annual_leave_days'        => $this->sanitizeNumber($rowArray[16] ?? 21),
                        'position'                 => $rowArray[4] ?? 'N/A',
                        'official_job_title'       => $rowArray[21] ?? ($rowArray[4] ?? 'N/A'),
                        'national_id_number'       => $nationalId ?: null,
                        'phone'                    => $rowArray[6] ?? null,
                        'emergency_phone'          => $rowArray[7] ?? null,
                        'bank_iban'                => $rowArray[8] ?? null,
                        'basic_salary'             => $this->sanitizeNumber($rowArray[9] ?? 0),
                        'housing_allowance'        => $this->sanitizeNumber($rowArray[10] ?? 0),
                        'transportation_allowance' => $this->sanitizeNumber($rowArray[11] ?? 0),
                        'other_allowances'         => $this->sanitizeNumber($rowArray[12] ?? 0),
                        'date_of_birth'            => $this->parseDate($rowArray[13] ?? null),
                        'hire_date'                => $this->parseDate($rowArray[14] ?? null) ?? now(),
                        'nationality'              => $nationality,
                        'residency_number'         => $residencyNum ?: null,
                        'residency_start_date'     => $this->parseDate($rowArray[19] ?? null),
                        'residency_end_date'       => $this->parseDate($rowArray[20] ?? null),
                        'shift_start_time'         => trim($rowArray[22] ?? null),
                        'shift_end_time'           => trim($rowArray[23] ?? null),
                        'work_type'                => $this->normalizeWorkType($rowArray[24] ?? 'full-time'),
                    ]
                );

                $this->successCount++;
                Log::info("Import OK: Row {$rowNumber} - {$email} imported for client {$this->clientId}");

            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                Log::error("Import FAIL: Row {$rowNumber} - " . $e->getMessage());
            }
        }
    }

    private function normalizeNationality($value): string
    {
        $v = mb_strtolower(trim($value));
        $map = [
            'سعودي' => 'Saudi',
            'saudi' => 'Saudi',
            'مصري' => 'Egyptian',
            'مصر' => 'Egyptian',
            'egyptian' => 'Egyptian',
            'يمني' => 'Yemeni',
            'yemeni' => 'Yemeni',
            'اردني' => 'Jordanian',
            'أردني' => 'Jordanian',
            'jordanian' => 'Jordanian',
            'سوري' => 'Syrian',
            'syrian' => 'Syrian',
            'سوداني' => 'Sudanese',
            'sudanese' => 'Sudanese',
            'فلسطيني' => 'Palestinian',
            'palestinian' => 'Palestinian',
            'لبناني' => 'Lebanese',
            'lebanese' => 'Lebanese',
            'مغربي' => 'Moroccan',
            'moroccan' => 'Moroccan',
            'تونسي' => 'Tunisian',
            'tunisian' => 'Tunisian',
            'جزائري' => 'Algerian',
            'algerian' => 'Algerian',
            'هندي' => 'Indian',
            'indian' => 'Indian',
            'باكستاني' => 'Pakistani',
            'pakistani' => 'Pakistani',
            'بنجلاديشي' => 'Bangladeshi',
            'bangladeshi' => 'Bangladeshi',
            'فلبيني' => 'Filipino',
            'filipino' => 'Filipino',
            'افغاني' => 'Afghan',
            'أفغاني' => 'Afghan',
            'afghan' => 'Afghan',
            'اندونيسي' => 'Indonesian',
            'إندونيسي' => 'Indonesian',
            'indonesian' => 'Indonesian',
            'نيبالي' => 'Nepalese',
            'nepalese' => 'Nepalese',
            'سريلانكي' => 'Sri Lankan',
            'sri lankan' => 'Sri Lankan',
            'اثيوبي' => 'Ethiopian',
            'إثيوبي' => 'Ethiopian',
            'ethiopian' => 'Ethiopian',
        ];

        foreach ($map as $key => $target) {
            if ($v === mb_strtolower($key)) {
                return $target;
            }
        }

        return $value;
    }

    private function normalizeWorkType($value): string
    {
        $v = mb_strtolower(trim($value));
        $map = [
            'دوام كامل' => 'full-time',
            'full-time' => 'full-time',
            'full time' => 'full-time',
            'دوام جزئي' => 'part-time',
            'part-time' => 'part-time',
            'part time' => 'part-time',
            'عمل عن بعد' => 'remote',
            'remote' => 'remote',
            'مؤقت' => 'temporary',
            'temporary' => 'temporary',
            'بالقطعة' => 'casual',
            'casual' => 'casual',
            'موسمي' => 'seasonal',
            'seasonal' => 'seasonal',
        ];

        foreach ($map as $key => $target) {
            if ($v === mb_strtolower($key)) {
                return $target;
            }
        }

        // Check if value contains special phrases (no more than 90 days etc)
        if (str_contains($v, '90')) {
            if (str_contains($v, 'مؤقت') || str_contains($v, 'temp')) return 'temporary';
            if (str_contains($v, 'قطعة') || str_contains($v, 'casual')) return 'casual';
        }

        return in_array($v, ['full-time', 'part-time', 'remote', 'temporary', 'casual', 'seasonal']) ? $v : 'full-time';
    }

    private function sanitizeNumber($value)
    {
        if ($value === null || $value === '') return 0;
        if (is_numeric($value)) return (float) $value;
        $cleanValue = preg_replace('/[^0-9.]/', '', strval($value));
        return is_numeric($cleanValue) ? floatval($cleanValue) : 0;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            } catch (\Exception $e) {
            }
        }
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
