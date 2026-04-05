<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\AnnouncementService;
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
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect('/')->with('error', __('Employee profile not found.'));
        }

        $announcements = $this->announcementService->getForEmployee($employee);
        
        return view('employee.announcements.index', compact('announcements'));
    }
}
