<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalizationJob extends Model
{
    protected $fillable = [
        'localization_decision_id',
        'occupation_code',
        'job_title_ar',
        'job_title_en',
    ];

    public function decision()
    {
        return $this->belongsTo(LocalizationDecision::class, 'localization_decision_id');
    }

    public function getJobTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->job_title_ar : ($this->job_title_en ?? $this->job_title_ar);
    }
}
