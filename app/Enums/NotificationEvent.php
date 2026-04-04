<?php

namespace App\Enums;

enum NotificationEvent: string
{
    case SUBSCRIPTION_EXPIRING = 'subscription_expiry_warning';

    public function availableVariables(): array
    {
        return match ($this) {
            self::SUBSCRIPTION_EXPIRING => ['days', 'tenant_name'],
        };
    }
    
    public function label(): string
    {
        return match ($this) {
            self::SUBSCRIPTION_EXPIRING => __('Subscription Expiring Warning'),
        };
    }
}
