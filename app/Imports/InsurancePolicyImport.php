<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeMedicalInsurance;
use App\Models\InsurancePolicy;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsurancePolicyImport implements ToCollection, WithHeadingRow
{
    private $policyId;
    private $clientId;
    public $skippedRows = [];
    public $rowsProcessed = 0;

    public function __construct($policyId)
    {
        $this->policyId = $policyId;
        $this->clientId = Auth::user()->client_id;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $normalizedRow = array_change_key_case($row->toArray(), CASE_LOWER);

                // Headers: national_id, insurance_class, cost
                $nationalId = trim($normalizedRow['national_id'] ?? $normalizedRow['national id'] ?? $normalizedRow['id'] ?? $normalizedRow['رقم الهوية'] ?? $normalizedRow['رقم الهوية الوطنية'] ?? $normalizedRow['رقم الإقامة'] ?? '');
                $class = trim($normalizedRow['class'] ?? $normalizedRow['insurance_class'] ?? $normalizedRow['insurance class'] ?? $normalizedRow['الفئة'] ?? $normalizedRow['فئة التأمين'] ?? $normalizedRow['الدرجة'] ?? 'A');
                $cost = $normalizedRow['cost'] ?? $normalizedRow['amount'] ?? $normalizedRow['التكلفة'] ?? $normalizedRow['المبلغ'] ?? 0;

                if (!$nationalId) {
                    continue;
                }

                $employee = Employee::where('client_id', $this->clientId)
                    ->where('national_id_number', $nationalId)
                    ->first();

                if (!$employee) {
                    $this->skippedRows[] = $nationalId;
                    continue;
                }

                $this->rowsProcessed++;

                EmployeeMedicalInsurance::updateOrCreate(
                    [
                        'client_id' => $this->clientId,
                        'employee_id' => $employee->id,
                        'insurance_policy_id' => $this->policyId,
                    ],
                    [
                        'insurance_class' => $class,
                        'cost' => $cost,
                    ]
                );
            }
        });
    }
}
