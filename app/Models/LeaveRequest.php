<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'client_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'reviewer_comment',
        'reviewed_at',
        'resumption_at',
        'resumption_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'resumption_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function actions()
    {
        return $this->hasMany(LeaveRequestAction::class)->latest();
    }

    public function latestAction()
    {
        return $this->hasOne(LeaveRequestAction::class)->latestOfMany();
    }

    /**
     * Calculate the number of days for this leave request.
     */
    public function getDaysCountAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPostponed(): bool
    {
        return $this->status === 'postponed';
    }

    public function isCurrentlyOnLeave(?Carbon $date = null): bool
    {
        $date = ($date ?? now())->startOfDay();

        return $this->isApproved()
            && is_null($this->resumption_at)
            && $date->between($this->start_date->copy()->startOfDay(), $this->end_date->copy()->startOfDay());
    }

    public function requiresResumption(?Carbon $date = null): bool
    {
        $date = ($date ?? now())->startOfDay();

        return $this->isApproved()
            && is_null($this->resumption_at)
            && $date->greaterThan($this->end_date->copy()->startOfDay());
    }

    public function canEmployeeRecordResumption(?Carbon $date = null): bool
    {
        return $this->requiresResumption($date);
    }

    public function elapsedLeaveDays(?Carbon $date = null): int
    {
        $date = ($date ?? now())->startOfDay();

        if ($date->lt($this->start_date->copy()->startOfDay())) {
            return 0;
        }

        if ($date->gt($this->end_date->copy()->startOfDay())) {
            return $this->days_count;
        }

        return $this->start_date->copy()->startOfDay()->diffInDays($date) + 1;
    }

    public function remainingLeaveDays(?Carbon $date = null): int
    {
        return max(0, $this->days_count - $this->elapsedLeaveDays($date));
    }
}
