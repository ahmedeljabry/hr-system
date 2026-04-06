<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Traits\BelongsToTenant;

class Announcement extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'client_id',
        'title',
        'body',
        'attachment',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}
