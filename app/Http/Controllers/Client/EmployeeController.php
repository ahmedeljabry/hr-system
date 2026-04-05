<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    private function getClientId(): int
    {
        return auth()->user()->client->id;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->list(
            $this->getClientId(),
            $request->input('search'),
        );
        return view('client.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('client.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->employeeService->create(
            $this->getClientId(),
            $request->validated(),
            $request->file('national_id_image'),
            $request->file('contract_image'),
            $request->file('cv_file'),
            $request->file('other_documents', []),
        );
        return redirect()->route('client.employees.index')->with('success', __('messages.employee_created'));
    }

    public function show(int $employee)
    {
        $employee = $this->employeeService->find($this->getClientId(), $employee);
        return view('client.employees.show', compact('employee'));
    }

    public function edit(int $employee)
    {
        $employee = $this->employeeService->find($this->getClientId(), $employee);
        return view('client.employees.edit', compact('employee'));
    }

    public function update(StoreEmployeeRequest $request, int $employee)
    {
        $this->employeeService->update(
            $this->getClientId(),
            $employee,
            $request->validated(),
            $request->file('national_id_image'),
            $request->file('contract_image'),
            $request->file('cv_file'),
            $request->file('other_documents', []),
        );
        return redirect()->route('client.employees.index')->with('success', __('messages.employee_updated'));
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(int $id)
    {
        try {
            \Illuminate\Support\Facades\Log::info("Destroy command received for employee ID: $id");
            
            $success = $this->employeeService->delete($this->getClientId(), $id);
            
            if ($success) {
                \Illuminate\Support\Facades\Log::info("Employee $id deleted successfully.");
                return redirect()->route('client.employees.index')->with('success', __('messages.employee_deleted') ?? 'Employee deleted successfully.');
            }
            
            \Illuminate\Support\Facades\Log::warning("Employee $id could not be deleted (not found or service failed).");
            return redirect()->route('client.employees.index')->with('error', 'Employee could not be found or has already been deleted.');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("CRITICAL ERROR during employee $id deletion: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return redirect()->route('client.employees.index')->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function importForm()
    {
        return view('client.employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        $countBefore = Employee::where('client_id', $this->getClientId())->count();

        $import = new EmployeesImport($this->getClientId());
        $import->import($request->file('file'));

        $failures = $import->failures();
        $countAfter = Employee::where('client_id', $this->getClientId())->count();
        $successCount = $countAfter - $countBefore;

        if ($failures->isNotEmpty()) {
            return redirect()->route('client.employees.import.form')
                ->with('warning', __('messages.import_errors'))
                ->with('import_failures', $failures)
                ->with('import_success_count', $successCount);
        }

        return redirect()->route('client.employees.index')
            ->with('success', __('messages.import_success', ['count' => $successCount]));
    }
}
