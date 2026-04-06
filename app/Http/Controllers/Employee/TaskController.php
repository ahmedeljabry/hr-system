<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', __('Employee profile not found.'));
        }
        
        $tasks = $this->taskService->getTasksForEmployee($employee);
        
        return view('employee.tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $employee = Auth::user()->employee;
        abort_unless($task->employee_id === $employee->id, 404);

        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $this->taskService->updateTaskStatus($task, $request->status);

        return back()->with('success', __('Task status updated.'));
    }
}
