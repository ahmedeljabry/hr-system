<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * Boot the trait and register the global scope for multi-tenancy.
     */
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('client_id', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $clientId = Auth::user()->client_id;
                
                if ($clientId) {
                    $builder->where('client_id', $clientId);
                }
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                if (!$model->client_id) {
                    $model->client_id = Auth::user()->client_id;
                }
            }
        });
    }
}
