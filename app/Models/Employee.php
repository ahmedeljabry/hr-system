<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($employee) {
            if (!$employee->slug) {
                $baseName = $employee->name_en ?: $employee->name_ar;
                $baseSlug = Str::slug($baseName);
                
                if (empty($baseSlug)) {
                    $baseSlug = 'emp-' . strtolower(Str::random(5));
                }
                
                $slug = $baseSlug;
                $counter = 2;

                // Keep incrementing until we find a unique slug
                while (static::where('slug', $slug)->where('id', '!=', $employee->id ?? 0)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $employee->slug = $slug;
            }
        });

        static::saved(function ($employee) {
            if ($employee->annual_leave_days !== null) {
                $employee->syncAnnualLeaveBalance();
            }
        });
    }

    /**
     * Syncs the employee's annual leave balance for the current year.
     */
    public function syncAnnualLeaveBalance(): void
    {
        $annualLeaveType = \App\Models\LeaveType::where('client_id', $this->client_id)
            ->where(function($q) {
                $q->where('name', 'Annual Leave')
                  ->orWhere('name', 'إجازة سنوية')
                  ->orWhere('name', 'like', '%سنوية%');
            })->first();

        if (!$annualLeaveType) {
            $annualLeaveType = \App\Models\LeaveType::create([
                'client_id' => $this->client_id,
                'name' => app()->getLocale() == 'ar' ? 'إجازة سنوية' : 'Annual Leave',
                'max_days_per_year' => 21,
                'gender' => 'all'
            ]);
        }

        \App\Models\LeaveBalance::updateOrCreate(
            [
                'employee_id' => $this->id,
                'leave_type_id' => $annualLeaveType->id,
                'year' => date('Y'),
            ],
            [
                'total_days' => $this->annual_leave_days,
            ]
        );
    }

    protected $fillable = [
        'client_id',
        'user_id',
        'slug',
        'name_ar',
        'name_en',
        'position',
        'national_id_number',
        'national_id_image',
        'contract_image',
        'cv_file',
        'other_documents',
        'bank_iban',
        'phone',
        'emergency_phone',
        'email',
        'gender',
        'annual_leave_days',
        'basic_salary',
        'housing_allowance',
        'transportation_allowance',
        'other_allowances',
        'hire_date',
        'status',
        'date_of_birth',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'annual_leave_days' => 'integer',
        'hire_date' => 'date',
        'date_of_birth' => 'date',
        'other_documents' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function salaryDeductions()
    {
        return $this->hasMany(SalaryDeduction::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function termination()
    {
        return $this->hasOne(EmployeeTermination::class);
    }

    public function getTotalSalaryAttribute(): float
    {
        return (float) ($this->basic_salary + $this->housing_allowance + $this->transportation_allowance + $this->other_allowances);
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : ($this->name_en ?? $this->name_ar);
    }
}

