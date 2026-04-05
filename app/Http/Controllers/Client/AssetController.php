<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Http\Request;
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
        $assets = Asset::with('employee')->latest()->paginate(10);
        return view('client.assets.index', compact('assets'));
    }

    public function create()
    {
        $employees = Auth::user()->client->employees()->orderBy('name')->get();
        return view('client.assets.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,NULL,id,client_id,' . Auth::user()->client_id,
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'returned_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        Asset::create($data);

        return redirect()->route('client.assets.index')->with('success', __('Asset recorded successfully.'));
    }

    public function edit(Asset $asset)
    {
        $employees = Auth::user()->client->employees()->orderBy('name')->get();
        return view('client.assets.edit', compact('asset', 'employees'));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $asset->id . ',id,client_id,' . Auth::user()->client_id,
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'returned_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        $asset->update($data);

        return redirect()->route('client.assets.index')->with('success', __('Asset updated successfully.'));
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('client.assets.index')->with('success', __('Asset deleted successfully.'));
    }
}
