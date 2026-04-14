<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeMedicalInsurance;
use App\Models\InsurancePolicy;
use App\Models\InsuranceCompany;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BulkInsurancePolicyImport implements ToCollection, WithHeadingRow
{
    private $clientId;
    private $policies = [];
    public $skippedRows = [];
    public $rowsProcessed = 0;

    public function __construct()
    {
        $this->clientId = Auth::user()->client_id;
    }

    public function collection(Collection $rows)
    {
        // 1. Validation Phase
        $currentRow = 1; // Heading is row 1, data starts at row 2
        $errors = [];

        foreach ($rows as $row) {
            $currentRow++;
            $normalizedRow = array_change_key_case($row->toArray(), CASE_LOWER);
            
            $companyName = trim($normalizedRow['insurance_company'] ?? $normalizedRow['company_name'] ?? $normalizedRow['insurance company'] ?? $normalizedRow['company'] ?? $normalizedRow['اسم الشركة'] ?? $normalizedRow['شركة التأمين'] ?? $normalizedRow['اسم شركة التأمين'] ?? $normalizedRow['الشركة'] ?? '');
            $policyNumber = trim($normalizedRow['policy_number'] ?? $normalizedRow['policy number'] ?? $normalizedRow['رقم البوليصة'] ?? $normalizedRow['رقم بوليصة التأمين'] ?? $normalizedRow['رقم العقد'] ?? '');
            $nationalId = trim($normalizedRow['national_id'] ?? $normalizedRow['national id'] ?? $normalizedRow['id'] ?? $normalizedRow['رقم الهوية'] ?? $normalizedRow['رقم الهوية الوطنية'] ?? $normalizedRow['رقم الإقامة'] ?? '');

            if (!$policyNumber || !$companyName || !$nationalId) {
                continue; // Skip empty/incomplete rows silently or you could add validation if they are strictly required
            }

            $company = InsuranceCompany::where('name', $companyName)->first();

            if (!$company) {
                // Collect errors instead of throwing immediately so we can show all or just throw the first one
                $errors[] = __('messages.company_not_found_error', [
                    'name' => $companyName,
                    'row' => $currentRow
                ]);
            }
        }

        if (count($errors) > 0) {
            // Reject the file with the first error message (or combine them)
            throw new \Exception(implode(' | ', array_unique($errors)));
        }

        // 2. Processing Phase (Wrap in transaction to ensure atomicity)
        DB::transaction(function () use ($rows) {
            $currentRow = 1;
            foreach ($rows as $row) {
                $currentRow++;
                $normalizedRow = array_change_key_case($row->toArray(), CASE_LOWER);
                
                $companyName = trim($normalizedRow['insurance_company'] ?? $normalizedRow['company_name'] ?? $normalizedRow['insurance company'] ?? $normalizedRow['company'] ?? $normalizedRow['اسم الشركة'] ?? $normalizedRow['شركة التأمين'] ?? $normalizedRow['اسم شركة التأمين'] ?? $normalizedRow['الشركة'] ?? '');
                $policyNumber = trim($normalizedRow['policy_number'] ?? $normalizedRow['policy number'] ?? $normalizedRow['رقم البوليصة'] ?? $normalizedRow['رقم بوليصة التأمين'] ?? $normalizedRow['رقم العقد'] ?? '');
                $startDate = $normalizedRow['start_date'] ?? $normalizedRow['start date'] ?? $normalizedRow['تاريخ البداية'] ?? $normalizedRow['تاريخ البدء'] ?? $normalizedRow['بداية التأمين'] ?? null;
                $endDate = $normalizedRow['end_date'] ?? $normalizedRow['end date'] ?? $normalizedRow['تاريخ النهاية'] ?? $normalizedRow['تاريخ الانتهاء'] ?? $normalizedRow['نهاية التأمين'] ?? null;
                
                $nationalId = trim($normalizedRow['national_id'] ?? $normalizedRow['national id'] ?? $normalizedRow['id'] ?? $normalizedRow['رقم الهوية'] ?? $normalizedRow['رقم الهوية الوطنية'] ?? $normalizedRow['رقم الإقامة'] ?? '');
                $class = trim($normalizedRow['class'] ?? $normalizedRow['insurance_class'] ?? $normalizedRow['insurance class'] ?? $normalizedRow['الفئة'] ?? $normalizedRow['فئة التأمين'] ?? $normalizedRow['الدرجة'] ?? 'A');
                $cost = $normalizedRow['cost'] ?? $normalizedRow['amount'] ?? $normalizedRow['التكلفة'] ?? $normalizedRow['المبلغ'] ?? 0;

                if (!$policyNumber || !$companyName || !$nationalId) {
                    continue;
                }

                $company = InsuranceCompany::where('name', $companyName)->first();

                // 2. Find or create the policy (Tenant-scoped)
                $policyKey = $this->clientId . '_' . $policyNumber;
                if (!isset($this->policies[$policyKey])) {
                    $parsedStart = $this->parseDate($startDate) ?? now();
                    $parsedEnd = $this->parseDate($endDate) ?? $parsedStart->copy()->addYear()->subDay();

                    $policy = InsurancePolicy::updateOrCreate(
                        [
                            'client_id' => $this->clientId,
                            'policy_number' => $policyNumber
                        ],
                        [
                            'insurance_company_id' => $company->id,
                            'start_date' => $parsedStart,
                            'end_date' => $parsedEnd,
                            'status' => 'active'
                        ]
                    );
                    $this->policies[$policyKey] = $policy;
                }
                $policy = $this->policies[$policyKey];

                // 3. Link Employee (Tenant-scoped)
                $employee = Employee::where('client_id', $this->clientId)
                    ->where('national_id_number', $nationalId)
                    ->first();

                if (!$employee) {
                    $this->skippedRows[] = $nationalId;
                    continue;
                }

                $this->rowsProcessed++;

                // 4. Update or Create the individual insurance record to prevent duplicates
                EmployeeMedicalInsurance::updateOrCreate(
                    [
                        'client_id' => $this->clientId,
                        'employee_id' => $employee->id,
                        'insurance_policy_id' => $policy->id,
                    ],
                    [
                        'insurance_class' => $class,
                        'cost' => $cost,
                    ]
                );
            }
        });
    }

    private function parseDate($date)
    {
        if (!$date) return null;
        try {
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
            }
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}
