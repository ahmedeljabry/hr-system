<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Get the unread notification count for an employee.
     */
    public function getUnreadCount(int $id, bool $isClient = false): int
    {
        $query = $isClient ? Notification::forClient($id) : Notification::forEmployee($id);
        return $query->unread()->count();
    }

    /**
     * Get paginated notifications for an employee.
     */
    public function getNotifications(int $id, int $perPage = 15, bool $isClient = false)
    {
        $query = $isClient ? Notification::forClient($id) : Notification::forEmployee($id);
        return $query->latest()->paginate($perPage);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $notificationId, int $id, bool $isClient = false): bool
    {
        $query = $isClient ? Notification::forClient($id) : Notification::forEmployee($id);
        $notification = $query->findOrFail($notificationId);
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
