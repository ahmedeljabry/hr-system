<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'total_days',
        'used_days',
    ];

    protected $casts = [
        'used_days' => 'decimal:1',
        'total_days' => 'decimal:1',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get the remaining balance for this type/year.
     */
    public function getRemainingAttribute(): float
    {
        $limit = $this->total_days ?? $this->leaveType->max_days_per_year;
        return max(0, $limit - $this->used_days);
    }
}
