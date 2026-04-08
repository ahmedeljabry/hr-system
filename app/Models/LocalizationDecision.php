<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalizationDecision extends Model
{
    protected $fillable = [
        'saudi_percentage',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
        'saudi_percentage' => 'decimal:2',
    ];

    public function jobs()
    {
        return $this->hasMany(LocalizationJob::class);
    }
}
