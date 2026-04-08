<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTermination extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'client_id',
        'reason_case',
        'article_number',
        'notice_period',
        'comments',
        'files',
        'terminated_at',
    ];

    protected $casts = [
        'files' => 'array',
        'terminated_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
