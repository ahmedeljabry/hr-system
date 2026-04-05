<?php

namespace App\Http\Controllers\Employee;

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
        $employeeId = auth()->user()->employee->id;
        $notifications = $this->service->getNotifications($employeeId, 10);
        return response()->json(['data' => $notifications->items()]);
    }

    public function read(int $notificationId): JsonResponse
    {
        $employeeId = auth()->user()->employee->id;
        $success = $this->service->markAsRead($notificationId, $employeeId);
        return response()->json(['success' => $success]);
    }
}
