<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect('/')->with('error', __('Employee profile not found.'));
        }

        return view('employee.profile.index', compact('employee'));
    }

    public function document($type)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            abort(404);
        }

        if (!in_array($type, ['national_id', 'contract'])) {
            abort(404);
        }

        $filePath = null;
        if ($type === 'national_id') {
            $filePath = $employee->national_id_image;
        } elseif ($type === 'contract') {
            $filePath = $employee->contract_image;
        }

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404);
        }

        return response()->file(storage_path('app/private/' . $filePath));
    }
}
