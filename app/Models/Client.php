<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employee;
use App\Models\Announcement;

use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($client) {
            if (!$client->slug) {
                $slug = Str::slug($client->name);
                if (empty($slug)) {
                    // Fallback for Arabic or non-Latin names
                    $slug = strtolower(Str::random(8));
                }
                $client->slug = $slug;
            }
        });

        static::created(function ($client) {
            // Create default Annual Leave Type for every new company
            $client->leaveTypes()->create([
                'name' => 'إجازة سنوية',
                'max_days_per_year' => 21,
                'gender' => 'all'
            ]);
        });
    }

    protected $fillable = [
        'name',
        'slug',
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
        return $this->status === 'active' && (!$this->subscription_end || $this->subscription_end->isFuture());
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

    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }
}

