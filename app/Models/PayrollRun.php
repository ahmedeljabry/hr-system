<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class PayrollRun extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['client_id', 'month', 'status', 'confirmed_at'];

    protected $casts = [
        'month' => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
}
