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
        'basic_salary',
        'hire_date',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hire_date' => 'date',
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
}

