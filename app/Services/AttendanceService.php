<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Get attendance records for a specific date.
     */
    public function getAttendanceForDate(Client $client, Carbon $date): Collection
    {
        return Attendance::where('client_id', $client->id)->whereDate('date', $date)->get();
    }

    public function bulkUpdateAttendance(Client $client, array $data): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($client, $data) {
            $date = $data['date'];
            $attendance = $data['attendance'];

            foreach ($attendance as $employeeId => $record) {
                // Ensure employee belongs to the current tenant
                $employeeExists = $client->employees()->where('id', $employeeId)->exists();
                if (!$employeeExists) {
                    continue; // Skip if invalid employee ID for this client
                }

                Attendance::updateOrCreate(
                    [
                        'client_id' => $client->id,
                        'employee_id' => $employeeId,
                        'date' => $date
                    ],
                    [
                        'status' => $record['status'],
                        'notes' => $record['notes'] ?? null
                    ]
                );
            }

            return true;
        });
    }
}
