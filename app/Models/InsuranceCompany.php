<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function policies()
    {
        return $this->hasMany(InsurancePolicy::class);
    }

    public function medicalInsurances()
    {
        return $this->hasManyThrough(EmployeeMedicalInsurance::class, InsurancePolicy::class);
    }
}
