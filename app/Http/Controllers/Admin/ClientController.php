<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $sortable = ['name', 'status', 'subscription_end'];
        $sort = in_array($request->get('sort'), $sortable) ? $request->get('sort') : 'name';
        $dir = $request->get('dir') === 'desc' ? 'desc' : 'asc';

        $query = Client::withCount('employees')
            ->orderBy($sort, $dir);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $clients = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($clients);
        }

        return view('admin.clients.index', compact('clients', 'sort', 'dir'));
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $employees = $client->employees()->with('user')->get();
        return view('admin.clients.show', compact('client', 'employees'));
    }

    /**
     * Update client status.
     */
    public function updateStatus(Request $request, Client $client)
    {
        $data = $request->validate([
            'status' => 'required|in:active,suspended,expired',
        ]);

        $old = $client->status;
        $this->subscriptionService->toggleStatus($client, $data['status']);

        Log::channel('daily')->info('ADMIN_ACTION', [
            'admin_id' => Auth::id(),
            'action' => 'status_change',
            'target' => 'clients',
            'record_id' => $client->id,
            'old' => $old,
            'new' => $data['status'],
        ]);

        return back()->with('success', __('messages.subscription_status_updated'));
    }

    /**
     * Update subscription end date.
     */
    public function updateSubscription(Request $request, Client $client)
    {
        $data = $request->validate([
            'subscription_end' => 'required|date',
        ]);

        $this->subscriptionService->setEndDate($client, $data['subscription_end']);

        return back()->with('success', __('messages.subscription_date_updated'));
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Request $request, Client $client)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($client) {
                // 1. Delete all users belonging to this client
                \App\Models\User::where('client_id', $client->id)->delete();

                // 2. Delete the client itself
                // Database cascading handles: employees, announcements, notifications, payroll_runs
                $client->delete();
            });

            Log::channel('daily')->info('ADMIN_ACTION', [
                'admin_id' => Auth::id(),
                'action' => 'client_deleted',
                'target' => 'clients',
                'record_id' => $client->id,
                'client_name' => $client->name,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => __('messages.client_deleted_successfully')]);
            }
            return redirect()->route('admin.clients.index')->with('success', __('messages.client_deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Client deletion failed: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('messages.delete_failed')], 500);
            }
            return back()->with('error', __('messages.delete_failed'));
        }
    }

    /**
     * Remove multiple clients from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', __('messages.no_clients_selected'));
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($ids) {
                // Delete all users belonging to these clients
                \App\Models\User::whereIn('client_id', $ids)->delete();

                // Delete the clients
                Client::whereIn('id', $ids)->delete();
            });

            Log::channel('daily')->info('ADMIN_ACTION', [
                'admin_id' => Auth::id(),
                'action' => 'bulk_client_deleted',
                'target' => 'clients',
                'count' => count($ids),
                'ids' => $ids,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => __('messages.clients_deleted_successfully')]);
            }
            return back()->with('success', __('messages.clients_deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Bulk client deletion failed: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('messages.delete_failed')], 500);
            }
            return back()->with('error', __('messages.delete_failed'));
        }
    }
}
