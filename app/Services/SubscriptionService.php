<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Update client subscription status.
     * @param Client $client
     * @param string $status (active/suspended/expired)
     */
    public function toggleStatus(Client $client, string $status): void
    {
        $allowed = ['active', 'suspended', 'expired'];
        if (in_array($status, $allowed)) {
            $client->status = $status;
            $client->save();
        }
    }

    /**
     * Set subscription end date.
     * @param Client $client
     * @param string $date (Y-m-d)
     */
    public function setEndDate(Client $client, string $date): void
    {
        // Simple logic for setting end date, validation should happen in Controller/Request
        $client->subscription_end = Carbon::parse($date);
        $client->save();
    }

    /**
     * Check if client subscription is currently active.
     */
    public function isActive(Client $client): bool
    {
        return $client->isActive();
    }
}
