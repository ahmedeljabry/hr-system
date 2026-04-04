<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipLineItem extends Model
{
    protected $fillable = ['payslip_id', 'component_name', 'type', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }
}
