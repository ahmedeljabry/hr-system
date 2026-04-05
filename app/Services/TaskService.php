<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    /**
     * Create or assign a task.
     */
    public function createTask(array $data, Client $client): Task
    {
        return Task::create($data);
    }

    /**
     * Retrieve tasks for an employee. (Read-Only)
     */
    public function getTasksForEmployee(Employee $employee): Collection
    {
        return Task::where('employee_id', $employee->id)->get();
    }
}
