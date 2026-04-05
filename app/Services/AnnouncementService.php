<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Client;
use App\Models\Employee;

class AnnouncementService
{
    public function getForClient(Client $client, int $perPage = 10)
    {
        return $client->announcements()->latest('published_at')->paginate($perPage);
    }

    public function create(Client $client, array $data)
    {
        return $client->announcements()->create($data);
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
