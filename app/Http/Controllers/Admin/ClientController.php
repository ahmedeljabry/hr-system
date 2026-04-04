<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Display a listing of clients.
     */
    public function index()
    {
        $clients = Client::latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Update client status.
     */
    public function updateStatus(Request $request, Client $client)
    {
        $data = $request->validate([
            'status' => 'required|in:active,suspended,expired',
        ]);

        $this->subscriptionService->toggleStatus($client, $data['status']);

        return back()->with('success', 'تم تحديث حالة الاشتراك بنجاح.');
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
