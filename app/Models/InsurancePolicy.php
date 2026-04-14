<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurancePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'insurance_company_id',
        'policy_number',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function employeeInsurances()
    {
        return $this->hasMany(EmployeeMedicalInsurance::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_medical_insurances', 'insurance_policy_id', 'employee_id')
            ->withPivot(['insurance_class', 'cost'])
            ->withTimestamps();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date ? $this->end_date->isPast() : false;
    }

    public function getEffectiveStatusAttribute(): string
    {
        if ($this->is_expired) {
            return 'expired';
        }
        return $this->status; // active or other
    }
}
