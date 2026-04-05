<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = Task::with('employee')->latest()->paginate(10);
        return view('client.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = Auth::user()->client->employees()->orderBy('name')->get();
        return view('client.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $this->taskService->createTask($data, Auth::user()->client);

        return redirect()->route('client.tasks.index')->with('success', __('Task created successfully.'));
    }

    public function edit(Task $task)
    {
        $employees = Auth::user()->client->employees()->orderBy('name')->get();
        return view('client.tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update($data);

        return redirect()->route('client.tasks.index')->with('success', __('Task updated successfully.'));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('client.tasks.index')->with('success', __('Task deleted successfully.'));
    }
}
