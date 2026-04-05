<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Get the unread notification count for an employee.
     */
    public function getUnreadCount(int $employeeId): int
    {
        return Notification::forEmployee($employeeId)->unread()->count();
    }

    /**
     * Get paginated notifications for an employee.
     */
    public function getNotifications(int $employeeId, int $perPage = 15)
    {
        return Notification::forEmployee($employeeId)->latest()->paginate($perPage);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $notificationId, int $employeeId): bool
    {
        $notification = Notification::forEmployee($employeeId)->findOrFail($notificationId);
        if (is_null($notification->read_at)) {
            $notification->read_at = now();
            return $notification->save();
        }
        return false;
    }

    /**
     * Create a new notification for an employee.
     */
    public function createNotification(array $data): Notification
    {
        return Notification::create($data);
    }
}
