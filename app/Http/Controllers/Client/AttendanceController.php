<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $client = Auth::user()->client;
        $date = $request->get('date', now()->format('Y-m-d'));
        
        try {
            $carbonDate = Carbon::parse($date);
        } catch (\Exception $e) {
            $carbonDate = now();
            $date = $carbonDate->format('Y-m-d');
        }
        
        $employees = $client->employees()->orderBy('name_ar')->get();
        $records = $this->attendanceService->getAttendanceForDate($client, $carbonDate)->keyBy('employee_id');

        return view('client.attendance.index', compact('employees', 'records', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,leave',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        $this->attendanceService->bulkUpdateAttendance(Auth::user()->client, $request->only('date', 'attendance'));
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.attendance_updated') ?? __('Attendance updated successfully.')
            ]);
        }

        return redirect()->back()->with('success', __('messages.attendance_updated') ?? __('Attendance updated successfully.'));
    }
}
