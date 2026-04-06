<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    protected AnnouncementService $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    private function getClient()
    {
        return Auth::user()->client;
    }

    public function index()
    {
        $announcements = $this->announcementService->getForClient($this->getClient());
        return view('client.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('client.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $this->announcementService->create($this->getClient(), $validated);

        return redirect()->route('client.announcements.index')->with('success', __('messages.employee_created'));
    }

    public function edit(Announcement $announcement)
    {
        $client = $this->getClient();
        abort_unless($announcement->client_id === $client->id, 403, __('messages.unauthorized'));

        return view('client.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $client = $this->getClient();
        abort_unless($announcement->client_id === $client->id, 403, __('messages.unauthorized'));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $this->announcementService->update($announcement, $validated);

        return redirect()->route('client.announcements.index')->with('success', __('messages.employee_updated'));
    }

    public function destroy(Announcement $announcement)
    {
        $client = $this->getClient();
        abort_unless($announcement->client_id === $client->id, 403, __('messages.unauthorized'));

        $this->announcementService->delete($announcement);

        return redirect()->route('client.announcements.index')->with('success', __('Announcement deleted successfully.'));
    }
}
