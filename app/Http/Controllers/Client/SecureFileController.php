<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileController extends Controller
{
    private function getClient()
    {
        return Auth::user()->client;
    }

    /**
     * Serve a task attachment through authenticated download.
     * Verifies the requesting user's client_id matches the task's client_id.
     */
    public function taskAttachment(Request $request, Task $task, int $index): StreamedResponse
    {
        $client = $this->getClient();

        // Verify tenant isolation
        abort_unless($task->client_id === $client->id, 403, __('messages.unauthorized'));

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
     * Verifies the requesting user's client_id matches the announcement's client_id.
     */
    public function announcementAttachment(Request $request, Announcement $announcement, int $index): StreamedResponse
    {
        $client = $this->getClient();

        // Verify tenant isolation
        abort_unless($announcement->client_id === $client->id, 403, __('messages.unauthorized'));

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
