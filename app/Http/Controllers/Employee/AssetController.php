<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    protected AssetService $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function index()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', __('Employee profile not found.'));
        }
        
        $assets = $this->assetService->getAssetsForEmployee($employee);
        
        return view('employee.assets.index', compact('assets'));
    }
}
