<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'client_id',
        'type',
        'title',
        'message',
        'read_at',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTitleAttribute($value)
    {
        if ($this->isJson($value)) {
            $data = json_decode($value, true);
            $params = $this->translateParams($data['params'] ?? []);
            return __($data['key'], $params);
        }
        return __($value);
    }

    public function getMessageAttribute($value)
    {
        if ($this->isJson($value)) {
            $data = json_decode($value, true);
            $params = $this->translateParams($data['params'] ?? []);
            return __($data['key'], $params);
        }
        return __($value);
    }

    private function translateParams(array $params): array
    {
        foreach ($params as $key => $value) {
            if (is_string($value) && str_starts_with($value, 'messages.')) {
                $params[$key] = __($value);
            }
        }
        return $params;
    }

    private function isJson($string)
    {
        if (!is_string($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}
