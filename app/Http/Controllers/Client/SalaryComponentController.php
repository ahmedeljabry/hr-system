<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\SalaryComponentService;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function __construct(private SalaryComponentService $service) {}

    private function getClientId(): int
    {
        return auth()->user()->client->id;
    }

    public function index(int $employee)
    {
        $emp = $this->service->getEmployeeComponents($this->getClientId(), $employee);
        return view('client.employees.salary-components', ['employee' => $emp]);
    }

    public function store(Request $request, int $employee)
    {
        $data = $request->validate([
            'type' => ['required', 'in:allowance,deduction'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $this->service->create($this->getClientId(), $employee, $data);

        return redirect()
            ->route('client.salary-components.index', $employee)
            ->with('success', __('messages.component_created'));
    }

    public function update(Request $request, int $employee, int $component)
    {
        $data = $request->validate([
            'type' => ['required', 'in:allowance,deduction'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $this->service->update($this->getClientId(), $employee, $component, $data);

        return redirect()
            ->route('client.salary-components.index', $employee)
            ->with('success', __('messages.component_updated'));
    }

    public function destroy(int $employee, int $component)
    {
        $this->service->delete($this->getClientId(), $employee, $component);

        return redirect()
            ->route('client.salary-components.index', $employee)
            ->with('success', __('messages.component_deleted'));
    }
}
