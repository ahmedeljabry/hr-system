<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index()
    {
        return view('employee.leaves.index');
    }
}
