<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Client;
use App\Models\Employee;

class AnnouncementService
{
    public function __construct(protected NotificationService $notificationService) {}

    public function getForClient(Client $client, int $perPage = 10)
    {
        return $client->announcements()->latest('published_at')->paginate($perPage);
    }

    public function create(Client $client, array $data)
    {
        $announcement = $client->announcements()->create($data);

        // Notify all employees of this client
        $employees = $client->employees;
        foreach ($employees as $employee) {
            $this->notificationService->createNotification([
                'employee_id' => $employee->id,
                'type' => 'new_announcement',
                'title' => 'messages.new_announcement',
                'message' => json_encode(['key' => 'messages.announcement_new_msg', 'params' => ['title' => $announcement->title]]),
                'related_type' => Announcement::class,
                'related_id' => $announcement->id,
            ]);
        }

        return $announcement;
    }

    public function update(Announcement $announcement, array $data)
    {
        $announcement->update($data);
        return $announcement;
    }

    public function delete(Announcement $announcement)
    {
        return $announcement->delete();
    }

    public function getForEmployee(Employee $employee, int $perPage = 10)
    {
        return Announcement::where('client_id', $employee->client_id)
            ->latest('published_at')
            ->paginate($perPage);
    }
}
