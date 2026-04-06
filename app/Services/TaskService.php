<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    public function __construct(protected NotificationService $notificationService) {}
    /**
     * Create or assign a task.
     */
    public function createTask(array $data, Client $client): Task
    {
        $task = Task::create($data);

        // Notify Employee
        $this->notificationService->createNotification([
            'employee_id' => $task->employee_id,
            'type' => 'task_assigned',
            'title' => 'messages.task_assigned',
            'message' => json_encode(['key' => 'messages.task_assigned_msg', 'params' => ['title' => $task->title]]),
            'related_type' => Task::class,
            'related_id' => $task->id,
        ]);

        return $task;
    }

    /**
     * Retrieve tasks for an employee. (Read-Only)
     */
    public function getTasksForEmployee(Employee $employee): Collection
    {
        return Task::where('employee_id', $employee->id)->get();
    }

    /**
     * Update task status (by employee).
     */
    public function updateTaskStatus(Task $task, string $status): bool
    {
        $updated = $task->update(['status' => $status]);
        
        if ($updated) {
            // Notify Client
            $this->notificationService->createNotification([
                'client_id' => $task->employee->client_id,
                'type' => 'task_status_updated',
                'title' => 'messages.task_status_updated',
                'message' => json_encode([
                    'key' => 'messages.task_status_updated_msg',
                    'params' => [
                        'name' => $task->employee->name,
                        'title' => $task->title,
                        'status' => 'messages.status_' . $status,
                    ]
                ]),
                'related_type' => Task::class,
                'related_id' => $task->id,
            ]);
        }
        
        return $updated;
    }
}
