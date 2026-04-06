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

    private function getClient()
    {
        return Auth::user()->client;
    }

    public function index()
    {
        $client = $this->getClient();
        $tasks = Task::where('client_id', $client->id)
            ->with('employee')
            ->latest()
            ->paginate(10);
        return view('client.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = $this->getClient()->employees()->orderBy('name')->get();
        return view('client.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $client = $this->getClient();

        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:todo,in_progress,done',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $data['status'] = $data['status'] ?? 'todo';

        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('task_attachments', 'public');
            }
            $data['attachments'] = $paths;
        }

        // Validate employee belongs to this client
        if (!empty($data['employee_id'])) {
            $employeeBelongsToClient = $client->employees()->where('id', $data['employee_id'])->exists();
            abort_unless($employeeBelongsToClient, 403, __('messages.unauthorized'));
        }

        $this->taskService->createTask($data, $client);

        return redirect()->route('client.tasks.index')->with('success', __('Task created successfully.'));
    }

    public function edit(Task $task)
    {
        $client = $this->getClient();
        abort_unless($task->client_id === $client->id, 403, __('messages.unauthorized'));

        $employees = $client->employees()->orderBy('name')->get();
        return view('client.tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $client = $this->getClient();
        abort_unless($task->client_id === $client->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,done',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        if ($request->hasFile('attachments')) {
            $existingAttachments = $task->attachments ?? [];
            $newPaths = [];
            foreach ($request->file('attachments') as $file) {
                $newPaths[] = $file->store('task_attachments', 'public');
            }
            $data['attachments'] = array_merge($existingAttachments, $newPaths);
        }

        // Validate employee belongs to this client
        if (!empty($data['employee_id'])) {
            $employeeBelongsToClient = $client->employees()->where('id', $data['employee_id'])->exists();
            abort_unless($employeeBelongsToClient, 403, __('messages.unauthorized'));
        }

        $task->update($data);

        return redirect()->route('client.tasks.index')->with('success', __('Task updated successfully.'));
    }

    public function destroy(Task $task)
    {
        $client = $this->getClient();
        abort_unless($task->client_id === $client->id, 403, __('messages.unauthorized'));

        $task->delete();
        return redirect()->route('client.tasks.index')->with('success', __('Task deleted successfully.'));
    }
}
