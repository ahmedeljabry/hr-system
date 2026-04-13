<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'resumed_at',
        'resumption_recorded_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'resumed_at' => 'datetime',
        'resumption_recorded_at' => 'datetime',
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

    public function hasResumed(): bool
    {
        return $this->resumed_at !== null;
    }

    public function requiresResumption(?CarbonInterface $at = null): bool
    {
        $reference = $at ?? now();

        return $this->isApproved()
            && ! $this->hasResumed()
            && $this->start_date->copy()->startOfDay()->lte($reference);
    }

    public function isCurrentlyOnLeave(?CarbonInterface $at = null): bool
    {
        $reference = $at ?? now();

        return $this->requiresResumption($reference)
            && $this->end_date->copy()->endOfDay()->gte($reference);
    }

    public function canEmployeeRecordResumption(?CarbonInterface $at = null): bool
    {
        return $this->requiresResumption($at);
    }

    public function canClientManageResumption(?CarbonInterface $at = null): bool
    {
        $reference = $at ?? now();

        return $this->isApproved()
            && $this->start_date->copy()->startOfDay()->lte($reference);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeAwaitingResumption(Builder $query): Builder
    {
        return $query->whereNull('resumed_at');
    }

    public function scopeStarted(Builder $query, CarbonInterface|string|null $at = null): Builder
    {
        $reference = $at instanceof CarbonInterface
            ? $at
            : Carbon::parse($at ?? now());

        return $query->whereDate('start_date', '<=', $reference->toDateString());
    }
}
