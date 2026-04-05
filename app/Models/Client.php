<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employee;
use App\Models\Announcement;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_start',
        'subscription_end',
        'status',
    ];

    protected $casts = [
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function isNearExpiry(int $days = 7): bool
    {
        if (!$this->subscription_end) {
            return false;
        }
        return $this->subscription_end->isFuture()
            && now()->diffInDays($this->subscription_end, false) <= $days;
    }

    public function payrollRuns()
    {
        return $this->hasMany(PayrollRun::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}

