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

    private function getClient()
    {
        return Auth::user()->client;
    }

    public function index()
    {
        $client = $this->getClient();
        $assets = Asset::where('client_id', $client->id)
            ->with('employee')
            ->latest()
            ->paginate(10);
        return view('client.assets.index', compact('assets'));
    }

    public function create()
    {
        $employees = $this->getClient()->employees()->orderBy('name_ar')->get();
        return view('client.assets.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $client = $this->getClient();

        $data = $request->validate([
            'employee_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('employees', 'id')->where('client_id', $client->id)
            ],
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,NULL,id,client_id,' . $client->id,
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'returned_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        // Validate employee belongs to this client
        if (!empty($data['employee_id'])) {
            $employeeBelongsToClient = $client->employees()->where('id', $data['employee_id'])->exists();
            abort_unless($employeeBelongsToClient, 403, __('messages.unauthorized'));
        }

        $data['client_id'] = $client->id;
        $asset = Asset::create($data);

        if ($asset->employee_id) {
            \App\Models\Notification::create([
                'employee_id' => $asset->employee_id,
                'type' => 'asset_assigned',
                'title' => json_encode(['key' => 'messages.asset_assigned']),
                'message' => json_encode([
                    'key' => 'messages.asset_assigned_msg',
                    'params' => ['type' => $asset->type, 'serial' => $asset->serial_number ?? '-']
                ]),
                'related_type' => Asset::class,
                'related_id' => $asset->id,
            ]);
        }

        return redirect()->route('client.assets.index')->with('success', __('messages.asset_created'));
    }

    public function edit(Asset $asset)
    {
        $client = $this->getClient();
        abort_unless($asset->client_id === $client->id, 403, __('messages.unauthorized'));

        $employees = $client->employees()->orderBy('name_ar')->get();
        return view('client.assets.edit', compact('asset', 'employees'));
    }

    public function update(Request $request, Asset $asset)
    {
        $client = $this->getClient();
        abort_unless($asset->client_id === $client->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'employee_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('employees', 'id')->where('client_id', $client->id)
            ],
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $asset->id . ',id,client_id,' . $client->id,
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'returned_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        // Validate employee belongs to this client
        if (!empty($data['employee_id'])) {
            $employeeBelongsToClient = $client->employees()->where('id', $data['employee_id'])->exists();
            abort_unless($employeeBelongsToClient, 403, __('messages.unauthorized'));
        }

        $oldEmployeeId = $asset->employee_id;
        $asset->update($data);

        if ($asset->employee_id && $asset->employee_id != $oldEmployeeId) {
            \App\Models\Notification::create([
                'employee_id' => $asset->employee_id,
                'type' => 'asset_assigned',
                'title' => json_encode(['key' => 'messages.asset_assigned']),
                'message' => json_encode([
                    'key' => 'messages.asset_assigned_msg',
                    'params' => ['type' => $asset->type, 'serial' => $asset->serial_number ?? '-']
                ]),
                'related_type' => Asset::class,
                'related_id' => $asset->id,
            ]);
        }

        return redirect()->route('client.assets.index')->with('success', __('messages.asset_updated'));
    }

    public function destroy(Asset $asset)
    {
        $client = $this->getClient();
        abort_unless($asset->client_id === $client->id, 403, __('messages.unauthorized'));

        $asset->delete();
        return redirect()->route('client.assets.index')->with('success', __('messages.asset_deleted'));
    }
}
