<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDeduction extends Model
{
    protected $fillable = [
        'employee_id',
        'client_id',
        'amount',
        'reason',
        'deduction_date',
        'is_applied',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deduction_date' => 'date',
        'is_applied' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
