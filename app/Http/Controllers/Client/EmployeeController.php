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
        // Priority 1: Use the tenant slug from the URL (vetted by middleware)
        $slug = request()->route('client_slug');
        if ($slug) {
            $client = \App\Models\Client::where('slug', $slug)->first();
            if ($client) return $client->id;
        }

        // Priority 2: Use the authenticated user's assigned client
        $user = auth()->user();
        return (int) ($user->client_id ?? $user->client?->id ?? 0);
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

    public function destroy(int $id)
    {
        try {
            $success = $this->employeeService->delete($this->getClientId(), $id);
            if ($success) {
                return redirect()->route('client.employees.index')->with('success', __('messages.employee_deleted'));
            }
            return redirect()->route('client.employees.index')->with('error', 'Employee not found.');
        } catch (\Exception $e) {
            return redirect()->route('client.employees.index')->with('error', 'Error: ' . $e->getMessage());
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

        $clientId = $this->getClientId();

        if (!$clientId || $clientId === 0) {
            \Illuminate\Support\Facades\Log::error("Import: Could not resolve client ID. User: " . auth()->id());
            return back()->with('error', __('messages.client_not_found') ?: 'Could not determine your company. Please log in again.');
        }

        try {
            $import = new EmployeesImport($clientId);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errors = $import->getErrors();

            if ($successCount === 0 && empty($errors)) {
                return back()->with('error', __('messages.import_no_data') ?: 'No valid employee data found in the file. Please check the column order matches the instructions.');
            }

            if ($successCount === 0 && !empty($errors)) {
                return back()->with('error', 'Import failed for all rows.')
                             ->with('import_errors', $errors);
            }

            // Some or all rows succeeded
            $redirect = redirect()->route('client.employees.index')
                ->with('success', __('messages.import_success', ['count' => $successCount]));

            if (!empty($errors)) {
                $redirect->with('import_errors', $errors);
            }

            return $redirect;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Import Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', __('messages.import_crash') ?: 'Import failed: ' . $e->getMessage());
        }
    }
}
