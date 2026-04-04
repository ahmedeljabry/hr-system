# Tasks: Client Dashboard & Employee Management (Phase 2)

**Input**: Design documents from `/specs/002-employee-management/`
**Prerequisites**: Phase 1 complete (auth, roles, subscription, localization)
**Constitution**: TDD first, Services hold logic, Eloquent only, multi-tenant isolation via `client_id`

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story (US1 = Employee CRUD, US2 = Excel Import, US3 = Dashboard & Banner)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Install the Laravel-Excel package dependency required for US2.

- [X] T001 Install Maatwebsite/Laravel-Excel package by running `composer require maatwebsite/excel` in the project root `d:\freelance\login system`. After installation, verify the package is listed in `composer.json` under `require`. Publish the config by running `php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config`. This creates `config/excel.php`.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Create the `employees` migration, the `Employee` model, update the `Client` model, and build the `EmployeeService`. These are shared by ALL user stories.

**⚠️ CRITICAL**: No user story work can begin until this phase is complete.

### Migration

- [X] T002 Create the `employees` table migration. Run `php artisan make:migration create_employees_table`. Then open the generated file at `database/migrations/xxxx_xx_xx_xxxxxx_create_employees_table.php` and set the `up()` method to:

```php
Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('name');
    $table->string('position');
    $table->string('national_id_number');
    $table->string('national_id_image')->nullable();
    $table->string('contract_image')->nullable();
    $table->decimal('basic_salary', 10, 2);
    $table->date('hire_date');
    $table->softDeletes();
    $table->timestamps();

    $table->unique(['client_id', 'national_id_number']);
    $table->index('client_id');
});
```

The `down()` method should be `Schema::dropIfExists('employees');`. After writing, run `php artisan migrate` to apply.

### Employee Model

- [X] T003 Create the Employee model at `app/Models/Employee.php`. The file must contain:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id',
        'name',
        'position',
        'national_id_number',
        'national_id_image',
        'contract_image',
        'basic_salary',
        'hire_date',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hire_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Update Client Model

- [X] T004 Update the existing `app/Models/Client.php` file. Add an `employees()` relationship method AND a helper to check near-expiry. Add this import at the top: `use App\Models\Employee;`. Then add these two methods inside the class body (after the existing `isExpired()` method):

```php
public function employees()
{
    return $this->hasMany(Employee::class);
}

public function isNearExpiry(int $days = 7): bool
{
    if (!$this->subscription_end) {
        return false;
    }
    return $this->subscription_end->isFuture()
        && $this->subscription_end->diffInDays(now()) <= $days;
}
```

### Employee Service

- [X] T005 Create the EmployeeService at `app/Services/EmployeeService.php`. This service handles ALL employee business logic and enforces tenant isolation. Every method receives `$clientId` explicitly:

```php
<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function list(int $clientId, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Employee::where('client_id', $clientId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('national_id_number', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)->findOrFail($employeeId);
    }

    public function create(int $clientId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null): Employee
    {
        $data['client_id'] = $clientId;

        if ($nationalIdFile) {
            $data['national_id_image'] = $nationalIdFile->store("employees/{$clientId}/national_ids", 'private');
        }
        if ($contractFile) {
            $data['contract_image'] = $contractFile->store("employees/{$clientId}/contracts", 'private');
        }

        return Employee::create($data);
    }

    public function update(int $clientId, int $employeeId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null): Employee
    {
        $employee = $this->find($clientId, $employeeId);

        if ($nationalIdFile) {
            if ($employee->national_id_image) {
                Storage::disk('private')->delete($employee->national_id_image);
            }
            $data['national_id_image'] = $nationalIdFile->store("employees/{$clientId}/national_ids", 'private');
        }
        if ($contractFile) {
            if ($employee->contract_image) {
                Storage::disk('private')->delete($employee->contract_image);
            }
            $data['contract_image'] = $contractFile->store("employees/{$clientId}/contracts", 'private');
        }

        $employee->update($data);
        return $employee->fresh();
    }

    public function delete(int $clientId, int $employeeId): bool
    {
        $employee = $this->find($clientId, $employeeId);
        return $employee->delete(); // Soft delete
    }
}
```

### Form Request Validation

- [X] T006 [P] Create the form request class at `app/Http/Requests/StoreEmployeeRequest.php`. Run `php artisan make:request StoreEmployeeRequest`, then replace the contents with:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        $clientId = $this->user()->client->id ?? null;
        $employeeId = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('employees')->where('client_id', $clientId)->ignore($employeeId),
            ],
            'national_id_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
            'contract_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('messages.employee_name')]),
            'national_id_number.unique' => __('messages.national_id_duplicate'),
        ];
    }
}
```

### Localization Keys

- [X] T007 [P] Add employee-related translation keys to `lang/en/messages.php`. Open the file and add these keys to the existing array (before the closing `];`):

```php
    // Employee Management (Phase 2)
    'employees' => 'Employees',
    'employee_name' => 'Employee Name',
    'position' => 'Position',
    'national_id_number' => 'National ID Number',
    'national_id_image' => 'National ID Image',
    'contract_image' => 'Contract Image',
    'basic_salary' => 'Basic Salary',
    'hire_date' => 'Hire Date',
    'add_employee' => 'Add Employee',
    'edit_employee' => 'Edit Employee',
    'employee_details' => 'Employee Details',
    'import_employees' => 'Import Employees',
    'upload_excel' => 'Upload Excel File (.xlsx)',
    'import' => 'Import',
    'download_template' => 'Download Template',
    'import_success' => ':count employees imported successfully.',
    'import_errors' => 'Some rows failed validation. See details below.',
    'national_id_duplicate' => 'This National ID number already exists for your company.',
    'no_employees' => 'No employees found. Add your first employee!',
    'confirm_delete_employee' => 'Are you sure you want to delete this employee?',
    'employee_created' => 'Employee added successfully.',
    'employee_updated' => 'Employee updated successfully.',
    'employee_deleted' => 'Employee deleted successfully.',
    'total_employees' => 'Total Employees',
    'subscription_expiry_warning' => 'Your subscription expires in :days days. Please contact admin to renew.',
    'search_employees' => 'Search employees...',
    'actions' => 'Actions',
    'back' => 'Back',
    'cancel' => 'Cancel',
    'view' => 'View',
    'edit' => 'Edit',
```

- [X] T008 [P] Add employee-related translation keys to `lang/ar/messages.php`. Open the file and add these keys to the existing array (before the closing `];`):

```php
    // Employee Management (Phase 2)
    'employees' => 'الموظفون',
    'employee_name' => 'اسم الموظف',
    'position' => 'المنصب',
    'national_id_number' => 'رقم الهوية الوطنية',
    'national_id_image' => 'صورة الهوية الوطنية',
    'contract_image' => 'صورة العقد',
    'basic_salary' => 'الراتب الأساسي',
    'hire_date' => 'تاريخ التعيين',
    'add_employee' => 'إضافة موظف',
    'edit_employee' => 'تعديل بيانات الموظف',
    'employee_details' => 'تفاصيل الموظف',
    'import_employees' => 'استيراد الموظفين',
    'upload_excel' => 'رفع ملف إكسل (.xlsx)',
    'import' => 'استيراد',
    'download_template' => 'تحميل القالب',
    'import_success' => 'تم استيراد :count موظف بنجاح.',
    'import_errors' => 'فشلت بعض الصفوف في التحقق. انظر التفاصيل أدناه.',
    'national_id_duplicate' => 'رقم الهوية مسجل مسبقاً لهذه الشركة.',
    'no_employees' => 'لا يوجد موظفون. أضف أول موظف!',
    'confirm_delete_employee' => 'هل أنت متأكد من حذف هذا الموظف؟',
    'employee_created' => 'تمت إضافة الموظف بنجاح.',
    'employee_updated' => 'تم تحديث بيانات الموظف بنجاح.',
    'employee_deleted' => 'تم حذف الموظف بنجاح.',
    'total_employees' => 'إجمالي الموظفين',
    'subscription_expiry_warning' => 'ينتهي اشتراكك خلال :days يوم. يرجى التواصل مع الإدارة للتجديد.',
    'search_employees' => 'بحث عن موظف...',
    'actions' => 'إجراءات',
    'back' => 'رجوع',
    'cancel' => 'إلغاء',
    'view' => 'عرض',
    'edit' => 'تعديل',
```

**Checkpoint**: Foundation ready — Employee model, service, validation, and translations all exist. User story implementation can now begin.

---

## Phase 3: User Story 1 — Employee CRUD Management (Priority: P1) 🎯 MVP

**Goal**: Client can add, view, edit, delete employees from a paginated list. Files are stored privately. Tenant isolation is absolute.

**Independent Test**: Log in as a client → go to `/client/employees` → add employee → see them in list → edit → delete.

### Tests for User Story 1

> **Write these FIRST. They MUST fail before implementation.**

- [X] T009 [US1] Create the feature test file at `tests/Feature/Client/EmployeeTest.php`. This file tests the entire employee CRUD lifecycle and tenant isolation. Paste the following complete test class:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_client_can_view_employees_index(): void
    {
        Employee::factory()->count(3)->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->get('/client/employees');
        $response->assertStatus(200);
        $response->assertViewIs('client.employees.index');
    }

    public function test_client_can_create_employee(): void
    {
        Storage::fake('private');

        $response = $this->actingAs($this->clientUser)->post('/client/employees', [
            'name' => 'Ahmed Ali',
            'position' => 'Developer',
            'national_id_number' => 'NID12345',
            'basic_salary' => 5000.00,
            'hire_date' => '2026-01-15',
            'national_id_image' => UploadedFile::fake()->image('id.jpg'),
        ]);

        $response->assertRedirect('/client/employees');
        $this->assertDatabaseHas('employees', [
            'name' => 'Ahmed Ali',
            'client_id' => $this->client->id,
            'national_id_number' => 'NID12345',
        ]);
    }

    public function test_client_can_update_employee(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->put("/client/employees/{$employee->id}", [
            'name' => 'Updated Name',
            'position' => $employee->position,
            'national_id_number' => $employee->national_id_number,
            'basic_salary' => 6000,
            'hire_date' => $employee->hire_date->format('Y-m-d'),
        ]);

        $response->assertRedirect('/client/employees');
        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => 'Updated Name']);
    }

    public function test_client_can_delete_employee(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->delete("/client/employees/{$employee->id}");

        $response->assertRedirect('/client/employees');
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }

    public function test_client_cannot_access_other_tenants_employees(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)->get("/client/employees/{$otherEmployee->id}");
        $response->assertStatus(404);
    }

    public function test_duplicate_national_id_within_same_client_rejected(): void
    {
        Employee::factory()->create([
            'client_id' => $this->client->id,
            'national_id_number' => 'DUP123',
        ]);

        $response = $this->actingAs($this->clientUser)->post('/client/employees', [
            'name' => 'Duplicate',
            'position' => 'Tester',
            'national_id_number' => 'DUP123',
            'basic_salary' => 3000,
            'hire_date' => '2026-01-01',
        ]);

        $response->assertSessionHasErrors('national_id_number');
    }

    public function test_employee_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->clientUser)->post('/client/employees', []);
        $response->assertSessionHasErrors(['name', 'position', 'national_id_number', 'basic_salary', 'hire_date']);
    }
}
```

- [X] T010 [P] [US1] Create the Employee factory at `database/factories/EmployeeFactory.php`. Run `php artisan make:factory EmployeeFactory --model=Employee`. Replace the `definition()` method with:

```php
public function definition(): array
{
    return [
        'client_id' => \App\Models\Client::factory(),
        'name' => fake()->name(),
        'position' => fake()->jobTitle(),
        'national_id_number' => fake()->unique()->numerify('NID#########'),
        'basic_salary' => fake()->randomFloat(2, 1000, 20000),
        'hire_date' => fake()->date(),
    ];
}
```

Also ensure that `database/factories/ClientFactory.php` exists. If not, create it with `php artisan make:factory ClientFactory --model=Client` and set the `definition()`:

```php
public function definition(): array
{
    return [
        'name' => fake()->company(),
        'subscription_start' => now(),
        'subscription_end' => now()->addYear(),
        'status' => 'active',
    ];
}
```

And verify `database/factories/UserFactory.php` exists (it ships with Laravel). Ensure the factory supports a `client_id` field by checking if `client_id` is in the User model's `$fillable` array. If the User model at `app/Models/User.php` does NOT have `client_id` in its `$fillable`, add it.

### Implementation for User Story 1

- [X] T011 [US1] Create the EmployeeController at `app/Http/Controllers/Client/EmployeeController.php`:

```php
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
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
        );
        return redirect('/client/employees')->with('success', __('messages.employee_created'));
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
        );
        return redirect('/client/employees')->with('success', __('messages.employee_updated'));
    }

    public function destroy(int $employee)
    {
        $this->employeeService->delete($this->getClientId(), $employee);
        return redirect('/client/employees')->with('success', __('messages.employee_deleted'));
    }
}
```

- [X] T012 [US1] Update the client routes file at `routes/client.php`. Replace the entire file contents with:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\EmployeeController;

Route::middleware(['auth', 'role:client', 'check_subscription'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    // Employee CRUD
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});
```

- [X] T013 [US1] Create the employee list view at `resources/views/client/employees/index.blade.php`. This view must:
  - Extend `@extends('layouts.app')` (the existing app layout).
  - Show a search form (`GET /client/employees?search=...`) at the top.
  - Show a paginated HTML table with columns: Name, Position, National ID Number, Basic Salary, Hire Date, Actions (View | Edit | Delete).
  - Include an "Add Employee" button linking to `/client/employees/create`.
  - Use `{{ $employees->links() }}` for pagination.
  - All text strings MUST use `{{ __('messages.key') }}` for bilingual support.
  - Delete buttons must be inside a `<form method="POST" action="..." >@csrf @method('DELETE')</form>` with a `confirm()` JavaScript prompt using `__('messages.confirm_delete_employee')`.
  - If `$employees->isEmpty()`, show a friendly empty state message using `__('messages.no_employees')`.
  - Display any flash `success` message from the session at the top.
  - Style using the existing project CSS classes (dark/blue theme from Phase 1).

- [X] T014 [P] [US1] Create the add employee form view at `resources/views/client/employees/create.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Contain a `<form method="POST" action="/client/employees" enctype="multipart/form-data">@csrf`.
  - Include input fields for: name (text, required), position (text, required), national_id_number (text, required), basic_salary (number, step=0.01, required), hire_date (date, required), national_id_image (file, optional), contract_image (file, optional).
  - Display validation errors using `@error('field_name') <span class="text-danger">{{ $message }}</span> @enderror` next to each field.
  - All labels MUST use `{{ __('messages.key') }}`.
  - Include a "Save" submit button and a "Cancel" link back to `/client/employees`.

- [X] T015 [P] [US1] Create the edit employee form view at `resources/views/client/employees/edit.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Contain a `<form method="POST" action="/client/employees/{{ $employee->id }}" enctype="multipart/form-data">@csrf @method('PUT')`.
  - Pre-fill all input fields with `{{ old('field', $employee->field) }}`.
  - Show the currently uploaded file names (if any) next to the file inputs.
  - All labels MUST use `{{ __('messages.key') }}`.
  - Include an "Update" submit button and a "Cancel" link back to `/client/employees`.

- [X] T016 [P] [US1] Create the employee detail view at `resources/views/client/employees/show.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Display all employee fields in a card layout: Name, Position, National ID Number, Basic Salary (formatted as number with 2 decimals), Hire Date (formatted as `d/m/Y`).
  - If `$employee->national_id_image` is not null, show a link/button to download the file (links to `/files/employees/{{ $employee->id }}/national_id`).
  - If `$employee->contract_image` is not null, show a link/button to download the file (links to `/files/employees/{{ $employee->id }}/contract`).
  - Include "Edit" and "Back to List" buttons.
  - All labels MUST use `{{ __('messages.key') }}`.

- [X] T017 [US1] Run the test suite: `php artisan test --filter=EmployeeTest`. All 7 tests from T009 MUST pass. If any fail, debug and fix the specific controller/service/route issue until all pass. Do NOT modify the tests.

**Checkpoint**: Employee CRUD is fully functional. Client can add, view, edit, soft-delete employees. Tenant isolation verified by test.

---

## Phase 4: User Story 2 — Bulk Employee Import via Excel (Priority: P1)

**Goal**: Client can upload a `.xlsx` file to bulk-import employees. Invalid rows are reported with clear error messages.

**Independent Test**: Log in as client → go to import page → upload valid `.xlsx` → see new employees in list. Upload invalid file → see error report.

### Tests for User Story 2

- [X] T018 [US2] Create the import test file at `tests/Feature/Client/EmployeeImportTest.php`:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_client_can_view_import_form(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/client/employees/import/form');
        $response->assertStatus(200);
        $response->assertViewIs('client.employees.import');
    }

    public function test_import_requires_xlsx_file(): void
    {
        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', []);
        $response->assertSessionHasErrors('file');
    }

    public function test_import_rejects_non_xlsx(): void
    {
        $file = UploadedFile::fake()->create('employees.csv', 100, 'text/csv');
        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', [
            'file' => $file,
        ]);
        $response->assertSessionHasErrors('file');
    }

    public function test_successful_import_creates_employees(): void
    {
        $file = UploadedFile::fake()->create('employees.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/client/employees');
        $response->assertSessionHas('success');
    }
}
```

### Implementation for User Story 2

- [X] T019 [US2] Create the import class at `app/Imports/EmployeesImport.php`:

```php
<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;

    public function __construct(private int $clientId)
    {
    }

    public function model(array $row): Employee
    {
        return new Employee([
            'client_id' => $this->clientId,
            'name' => $row['name'],
            'position' => $row['position'],
            'national_id_number' => $row['national_id_number'] ?? $row['national_id'],
            'basic_salary' => $row['basic_salary'] ?? $row['salary'],
            'hire_date' => $row['hire_date'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_number' => ['required', 'string', 'max:100'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => __('messages.employee_name') . ' ' . __('validation.required'),
        ];
    }
}
```

- [X] T020 [US2] Add the import methods to the EmployeeController at `app/Http/Controllers/Client/EmployeeController.php`. Add these two methods at the bottom of the class (before the closing `}`):

```php
    public function importForm()
    {
        return view('client.employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        $import = new \App\Imports\EmployeesImport($this->getClientId());
        $import->import($request->file('file'));

        $failures = $import->failures();
        $successCount = \App\Models\Employee::where('client_id', $this->getClientId())->count();

        if ($failures->isNotEmpty()) {
            return redirect('/client/employees')
                ->with('warning', __('messages.import_errors'))
                ->with('import_failures', $failures);
        }

        return redirect('/client/employees')
            ->with('success', __('messages.import_success', ['count' => $successCount]));
    }
```

Also add these import routes to `routes/client.php` BEFORE the `{employee}` routes (to prevent route conflicts). Insert these two lines right after the `Route::post('/employees', ...)` line:

```php
    Route::get('/employees/import/form', [EmployeeController::class, 'importForm'])->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
```

- [X] T021 [US2] Create the import form view at `resources/views/client/employees/import.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show a `<form method="POST" action="/client/employees/import" enctype="multipart/form-data">@csrf`.
  - Include a file input for the `.xlsx` file with label `{{ __('messages.upload_excel') }}`.
  - Include an "Import" submit button. Include a "Cancel" link back to `/client/employees`.
  - Display validation errors for `file`.
  - Show a short instruction paragraph explaining the expected columns: Name, Position, National ID Number, Basic Salary, Hire Date.
  - All labels MUST use `{{ __('messages.key') }}`.

- [X] T022 [US2] Run the import tests: `php artisan test --filter=EmployeeImportTest`. All 3 tests MUST pass.

**Checkpoint**: Bulk import is functional. Client can upload Excel files and see results.

---

## Phase 5: User Story 3 — Dashboard Overview & Subscription Banner (Priority: P2)

**Goal**: Client dashboard shows employee count metric and a subscription expiry warning banner when subscription ends within 7 days.

**Independent Test**: Log in as client → dashboard shows total employee count → set subscription_end to 5 days from now → see warning banner.

### Tests for User Story 3

- [X] T023 [US3] Create the dashboard test file at `tests/Feature/Client/DashboardTest.php`:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_employee_count(): void
    {
        $client = Client::factory()->create(['status' => 'active']);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);
        Employee::factory()->count(5)->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        $response->assertSee('5');
    }

    public function test_dashboard_shows_expiry_warning_when_near(): void
    {
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(3),
        ]);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        $response->assertSee('3'); // Days remaining shown in banner
    }

    public function test_dashboard_no_warning_when_far_from_expiry(): void
    {
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(60),
        ]);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        // Should NOT contain the warning string
        $response->assertDontSee(__('messages.subscription_expiry_warning', ['days' => '60']));
    }
}
```

### Implementation for User Story 3

- [X] T024 [US3] Create the DashboardController at `app/Http/Controllers/Client/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;
        $employeeCount = $client->employees()->count();

        $daysUntilExpiry = null;
        $showExpiryWarning = false;

        if ($client->subscription_end) {
            $daysUntilExpiry = (int) now()->diffInDays($client->subscription_end, false);
            $showExpiryWarning = $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7;
        }

        return view('client.dashboard', compact(
            'client',
            'employeeCount',
            'daysUntilExpiry',
            'showExpiryWarning',
        ));
    }
}
```

- [X] T025 [US3] Update `routes/client.php` to use the DashboardController instead of the inline closure. Replace the existing dashboard route line:

**Replace this:**
```php
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');
```

**With this:**
```php
    Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
```

- [X] T026 [US3] Update the client dashboard view at `resources/views/client/dashboard.blade.php`. The view must:
  - Extend `@extends('layouts.app')`.
  - If `$showExpiryWarning` is true, display a prominent orange/yellow warning banner at the top containing `{{ __('messages.subscription_expiry_warning', ['days' => $daysUntilExpiry]) }}`.
  - Display a metrics card showing `{{ $employeeCount }}` as a large number with the label `{{ __('messages.total_employees') }}`.
  - Include a quick-action link/button to "Manage Employees" pointing to `/client/employees`.
  - Use the existing dark/blue premium CSS theme from Phase 1.
  - All text strings MUST use `{{ __('messages.key') }}` for bilingual support.

- [X] T027 [US3] Run the dashboard tests: `php artisan test --filter=DashboardTest`. All 3 tests MUST pass.

**Checkpoint**: Dashboard shows metrics and expiry warnings. All 3 user stories are independently functional.

---

## Phase 6: Secure File Access

**Purpose**: Serve private employee files (national ID, contract) securely via a controller that checks tenant ownership.

- [X] T028 Create a file controller at `app/Http/Controllers/Client/EmployeeFileController.php`:

```php
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeFileController extends Controller
{
    public function show(int $employee, string $type): StreamedResponse
    {
        $clientId = auth()->user()->client->id;
        $emp = Employee::where('client_id', $clientId)->findOrFail($employee);

        $path = match ($type) {
            'national_id' => $emp->national_id_image,
            'contract' => $emp->contract_image,
            default => abort(404),
        };

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path);
    }
}
```

- [X] T029 Add the file serving route to `routes/client.php`. Add this line inside the `Route::middleware(['auth', 'role:client', 'check_subscription'])` group (at the bottom of the group):

```php
    // Secure file serving (tenant-scoped)
    Route::get('/files/employees/{employee}/{type}', [\App\Http\Controllers\Client\EmployeeFileController::class, 'show'])->name('files.employee');
```

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final integration, full test run, and cleanup.

- [X] T030 [P] Ensure the private storage disk is configured. Open `config/filesystems.php` and verify that a disk named `private` exists in the `disks` array. If it does NOT exist, add:

```php
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => false,
    'throw' => false,
],
```

- [X] T031 Run the FULL test suite: `php artisan test`. ALL tests from Phase 1 AND Phase 2 must pass. The expected tests include:
  - `EmployeeTest` (7 tests)
  - `EmployeeImportTest` (3 tests)
  - `DashboardTest` (3 tests)
  - All existing Phase 1 tests (auth, role middleware, subscription, etc.)

  Fix any failures. Do NOT modify test files — fix the source code instead.

- [X] T032 [P] Review all Blade views for consistent styling: dark/blue theme, RTL support via `dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"` on the `<html>` tag, responsive layout on mobile screens, proper use of `{{ __('messages.key') }}` for every visible string.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — install package first
- **Foundational (Phase 2)**: Depends on Phase 1 — BLOCKS all user stories
- **US1 (Phase 3)**: Depends on Phase 2 — MVP deliverable
- **US2 (Phase 4)**: Depends on Phase 2 + T012 routes — can run parallel with US1 once routes exist
- **US3 (Phase 5)**: Depends on Phase 2 — independent of US1/US2
- **File Access (Phase 6)**: Depends on US1 (files stored by CRUD)
- **Polish (Phase 7)**: Depends on all phases

### Within Each User Story

1. Tests MUST be written and FAIL before implementation
2. Models before services
3. Services before controllers
4. Controllers before views
5. Core implementation before integration
6. Final test run to verify

### Parallel Opportunities

- T007 and T008 (translation files) can run in parallel
- T010 (factory) can run in parallel with T009 (tests, since tests need the factory)
- T014, T015, T016 (create/edit/show views) can all run in parallel
- T030 (config) can run in parallel with anything

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (T001)
2. Complete Phase 2: Foundation (T002–T008)
3. Complete Phase 3: Employee CRUD (T009–T017)
4. **STOP and VALIDATE**: Run `php artisan test --filter=EmployeeTest`
5. Deploy/demo if ready

### Full Phase 2 Delivery

1. Setup → Foundation → US1 → US2 → US3 → File Access → Polish
2. Each story adds value independently
3. Final: `php artisan test` — all green

---

## Notes

- [P] tasks = different files, no dependencies between them
- [USx] label maps task to specific user story for traceability
- Every code snippet is copy-paste ready for a cheaper LLM
- All strings use `__('messages.key')` for Arabic/English support
- Tenant isolation is enforced at the Service layer, not just middleware
- Commit after each task or logical group
