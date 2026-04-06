<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(private PayrollService $service) {}

    private function getClientId(): int
    {
        return auth()->user()->client->id;
    }

    public function index()
    {
        $runs = $this->service->getHistory($this->getClientId());
        return view('client.payroll.index', compact('runs'));
    }

    public function create()
    {
        return view('client.payroll.run');
    }

    public function store(Request $request)
    {
        $request->validate(['month' => ['required', 'date']]);

        try {
            $run = $this->service->runPayroll($this->getClientId(), $request->month);
            return redirect()
                ->route('client.payroll.show', $run->id)
                ->with('success', __('messages.payroll_run_created'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('client.payroll.create')
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $payrollRun)
    {
        $run = $this->service->findRun($this->getClientId(), $payrollRun);
        return view('client.payroll.show', compact('run'));
    }

    public function confirm(int $payrollRun)
    {
        try {
            $this->service->confirmRun($this->getClientId(), $payrollRun);
            return redirect()
                ->route('client.payroll.show', $payrollRun)
                ->with('success', __('messages.payroll_confirmed_success'));
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('client.payroll.show', $payrollRun)
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $payrollRun)
    {
        $this->service->deleteRun($this->getClientId(), $payrollRun);
        return redirect()->route('client.payroll.index')
            ->with('success', __('messages.payroll_deleted_success'));
    }
}
