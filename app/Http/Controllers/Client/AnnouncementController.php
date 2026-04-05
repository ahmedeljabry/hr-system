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

    public function index()
    {
        $announcements = $this->announcementService->getForClient(Auth::user()->client);
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
        ]);

        $this->announcementService->create(Auth::user()->client, $validated);

        return redirect()->route('client.announcements.index')->with('success', __('Announcement created successfully.'));
    }

    public function edit(Announcement $announcement)
    {
        return view('client.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        $this->announcementService->update($announcement, $validated);

        return redirect()->route('client.announcements.index')->with('success', __('Announcement updated successfully.'));
    }

    public function destroy(Announcement $announcement)
    {
        $this->announcementService->delete($announcement);

        return redirect()->route('client.announcements.index')->with('success', __('Announcement deleted successfully.'));
    }
}
