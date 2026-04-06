<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $service)
    {
    }

    public function api(): JsonResponse
    {
        $clientId = auth()->user()->client->id;
        $notifications = $this->service->getNotifications($clientId, 10, true);
        return response()->json(['data' => $notifications->items()]);
    }

    public function read(int $notificationId): JsonResponse
    {
        $clientId = auth()->user()->client->id;
        $success = $this->service->markAsRead($notificationId, $clientId, true);
        return response()->json(['success' => $success]);
    }
}
