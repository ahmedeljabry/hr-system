<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id',
        'name',
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
        'basic_salary',
        'housing_allowance',
        'transportation_allowance',
        'other_allowances',
        'hire_date',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'hire_date' => 'date',
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

    public function getTotalSalaryAttribute(): float
    {
        return (float) ($this->basic_salary + $this->housing_allowance + $this->transportation_allowance + $this->other_allowances);
    }
}

