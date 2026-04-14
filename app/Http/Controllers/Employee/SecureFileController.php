<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileController extends Controller
{
    private function getEmployee()
    {
        return Auth::user()->employee;
    }

    /**
     * Serve a task attachment through authenticated download.
     * Verifies the employee belongs to the same client as the task.
     */
    public function taskAttachment(Request $request, Task $task, int $index): StreamedResponse
    {
        $employee = $this->getEmployee();
        abort_unless($employee, 403, __('messages.unauthorized'));

        // Verify the task belongs to the employee's client
        abort_unless($task->client_id === $employee->client_id, 403, __('messages.unauthorized'));

        $attachments = $task->attachments ?? [];

        if (!isset($attachments[$index])) {
            abort(404);
        }

        $attachment = $attachments[$index];
        $path = is_array($attachment) ? ($attachment['path'] ?? $attachment) : $attachment;
        $fileName = is_array($attachment) ? ($attachment['name'] ?? basename($path)) : basename($path);

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path, $fileName);
    }

    /**
     * Serve an announcement attachment through authenticated download.
     * Verifies the employee belongs to the same client as the announcement.
     */
    public function announcementAttachment(Request $request, Announcement $announcement, int $index): StreamedResponse
    {
        $employee = $this->getEmployee();
        abort_unless($employee, 403, __('messages.unauthorized'));

        // Verify the announcement belongs to the employee's client
        abort_unless($announcement->client_id === $employee->client_id, 403, __('messages.unauthorized'));

        $attachments = $announcement->attachments ?? [];

        if (!isset($attachments[$index])) {
            abort(404);
        }

        $path = $attachments[$index];

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path, basename($path));
    }
}
