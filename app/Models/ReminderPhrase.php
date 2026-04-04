<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderPhrase extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_key',
        'text_en',
        'text_ar',
    ];

    protected $casts = [
        'event_key' => \App\Enums\NotificationEvent::class,
    ];
}
