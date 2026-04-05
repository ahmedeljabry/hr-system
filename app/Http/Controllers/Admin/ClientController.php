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

        return back()->with('success', __('Subscription status updated successfully.'));
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

        return back()->with('success', 'تم تحديث تاريخ انتهاء الاشتراك بنجاح.');
    }
}
