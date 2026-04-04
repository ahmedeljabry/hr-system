<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_start',
        'subscription_end',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
