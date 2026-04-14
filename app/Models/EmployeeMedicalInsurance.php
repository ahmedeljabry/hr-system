<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMedicalInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'employee_id',
        'insurance_policy_id',
        'insurance_class',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function insurancePolicy()
    {
        return $this->belongsTo(InsurancePolicy::class);
    }

    public function company()
    {
        return $this->hasOneThrough(
            InsuranceCompany::class,
            InsurancePolicy::class,
            'id', // Local key on InsurancePolicy
            'id', // Local key on InsuranceCompany
            'insurance_policy_id', // Foreign key on EmployeeMedicalInsurance
            'insurance_company_id' // Foreign key on InsurancePolicy
        );
    }
}
