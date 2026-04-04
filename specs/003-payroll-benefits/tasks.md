# Tasks: Payroll & Benefits

**Input**: Design documents from `/specs/003-payroll-benefits/`
**Prerequisites**: plan.md (required), spec.md (required), research.md, data-model.md, contracts/

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Create all database tables and model factories needed by every user story.

- [x] T001 Create migration for `salary_components` table at `database/migrations/xxxx_create_salary_components_table.php`. Run `php artisan make:migration create_salary_components_table` then set the `up()` method to:

```php
Schema::create('salary_components', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['allowance', 'deduction']);
    $table->string('name');
    $table->decimal('amount', 10, 2);
    $table->timestamps();
    $table->index(['employee_id', 'type']);
});
```

- [x] T002 Create migration for `payroll_runs` table at `database/migrations/xxxx_create_payroll_runs_table.php`. Run `php artisan make:migration create_payroll_runs_table` then set the `up()` method to:

```php
Schema::create('payroll_runs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->cascadeOnDelete();
    $table->date('month');
    $table->enum('status', ['draft', 'confirmed'])->default('draft');
    $table->timestamp('confirmed_at')->nullable();
    $table->timestamps();
    $table->index('client_id');
});
```

- [x] T003 Create migration for `payslips` table at `database/migrations/xxxx_create_payslips_table.php`. Run `php artisan make:migration create_payslips_table` then set the `up()` method to:

```php
Schema::create('payslips', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
    $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
    $table->decimal('basic_salary', 10, 2);
    $table->decimal('total_allowances', 10, 2)->default(0);
    $table->decimal('total_deductions', 10, 2)->default(0);
    $table->decimal('net_salary', 10, 2);
    $table->timestamps();
    $table->unique(['payroll_run_id', 'employee_id']);
    $table->index('employee_id');
});
```

- [x] T004 Create migration for `payslip_line_items` table at `database/migrations/xxxx_create_payslip_line_items_table.php`. Run `php artisan make:migration create_payslip_line_items_table` then set the `up()` method to:

```php
Schema::create('payslip_line_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payslip_id')->constrained()->cascadeOnDelete();
    $table->string('component_name');
    $table->enum('type', ['allowance', 'deduction']);
    $table->decimal('amount', 10, 2);
    $table->timestamps();
    $table->index(['payslip_id', 'type']);
});
```

- [x] T005 Run the migrations: `php artisan migrate`. All 4 tables MUST be created.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Create all Eloquent models, factories, relationships, and translation strings that every user story depends on.

**⚠️ CRITICAL**: No user story work can begin until this phase is complete.

- [x] T006 [P] Create the `SalaryComponent` model at `app/Models/SalaryComponent.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'name', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
```

- [x] T007 [P] Create the `PayrollRun` model at `app/Models/PayrollRun.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'month', 'status', 'confirmed_at'];

    protected $casts = [
        'month' => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
}
```

- [x] T008 [P] Create the `Payslip` model at `app/Models/Payslip.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id', 'employee_id', 'basic_salary',
        'total_allowances', 'total_deductions', 'net_salary',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function lineItems()
    {
        return $this->hasMany(PayslipLineItem::class);
    }
}
```

- [x] T009 [P] Create the `PayslipLineItem` model at `app/Models/PayslipLineItem.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipLineItem extends Model
{
    protected $fillable = ['payslip_id', 'component_name', 'type', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }
}
```

- [x] T010 Add relationships to the existing `Employee` model at `app/Models/Employee.php`. Add these two methods BEFORE the closing `}`:

```php
    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }
```

- [x] T011 Add the `payrollRuns` relationship to the existing `Client` model at `app/Models/Client.php`. Add this method BEFORE the closing `}`:

```php
    public function payrollRuns()
    {
        return $this->hasMany(PayrollRun::class);
    }
```

- [x] T012 [P] Create factory at `database/factories/SalaryComponentFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\SalaryComponent;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryComponentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'type' => fake()->randomElement(['allowance', 'deduction']),
            'name' => fake()->randomElement(['Housing', 'Transport', 'Insurance', 'Tax', 'Food']),
            'amount' => fake()->randomFloat(2, 100, 3000),
        ];
    }

    public function allowance(): static
    {
        return $this->state(['type' => 'allowance']);
    }

    public function deduction(): static
    {
        return $this->state(['type' => 'deduction']);
    }
}
```

- [x] T013 [P] Create factory at `database/factories/PayrollRunFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\PayrollRun;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollRunFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'month' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-01'),
            'status' => 'draft',
        ];
    }

    public function confirmed(): static
    {
        return $this->state([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }
}
```

- [x] T014 [P] Create factory at `database/factories/PayslipFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Payslip;
use App\Models\PayrollRun;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayslipFactory extends Factory
{
    public function definition(): array
    {
        $basic = fake()->randomFloat(2, 3000, 15000);
        $allowances = fake()->randomFloat(2, 500, 5000);
        $deductions = fake()->randomFloat(2, 100, 2000);

        return [
            'payroll_run_id' => PayrollRun::factory(),
            'employee_id' => Employee::factory(),
            'basic_salary' => $basic,
            'total_allowances' => $allowances,
            'total_deductions' => $deductions,
            'net_salary' => $basic + $allowances - $deductions,
        ];
    }
}
```

- [x] T015 [P] Add payroll translation strings to `lang/en/messages.php`. Append these entries BEFORE the closing `];`:

```php
    // Payroll & Benefits (Phase 3)
    'salary_components' => 'Salary Components',
    'allowance' => 'Allowance',
    'deduction' => 'Deduction',
    'component_name' => 'Component Name',
    'amount' => 'Amount',
    'type' => 'Type',
    'add_component' => 'Add Component',
    'edit_component' => 'Edit Component',
    'component_created' => 'Salary component added successfully.',
    'component_updated' => 'Salary component updated successfully.',
    'component_deleted' => 'Salary component deleted successfully.',
    'payroll' => 'Payroll',
    'payroll_run' => 'Run Payroll',
    'payroll_history' => 'Payroll History',
    'select_month' => 'Select Month',
    'run_payroll' => 'Run Payroll',
    'confirm_payroll' => 'Confirm Payroll',
    'payroll_draft' => 'Draft',
    'payroll_confirmed' => 'Confirmed',
    'payroll_run_created' => 'Payroll run created as draft. Review and confirm.',
    'payroll_confirmed_success' => 'Payroll run confirmed successfully.',
    'payroll_duplicate' => 'A confirmed payroll run already exists for this month.',
    'payroll_future_month' => 'Cannot run payroll for a future month.',
    'no_payroll_runs' => 'No payroll runs yet.',
    'total_net_payout' => 'Total Net Payout',
    'employee_count' => 'Employee Count',
    'run_date' => 'Run Date',
    'net_salary' => 'Net Salary',
    'total_allowances' => 'Total Allowances',
    'total_deductions' => 'Total Deductions',
    'payslips' => 'Payslips',
    'my_payslips' => 'My Payslips',
    'payslip_detail' => 'Payslip Detail',
    'no_payslips' => 'No payslips available.',
    'create_account' => 'Create Account',
    'create_employee_account' => 'Create Employee Login',
    'employee_account_created' => 'Employee account created successfully. Password: :password',
    'employee_already_has_account' => 'This employee already has a login account.',
    'employee_email' => 'Employee Email',
    'manage_payroll' => 'Manage Payroll',
```

- [x] T016 [P] Add payroll translation strings to `lang/ar/messages.php`. Append the Arabic equivalents BEFORE the closing `];`:

```php
    // Payroll & Benefits (Phase 3)
    'salary_components' => 'مكونات الراتب',
    'allowance' => 'بدل',
    'deduction' => 'خصم',
    'component_name' => 'اسم المكون',
    'amount' => 'المبلغ',
    'type' => 'النوع',
    'add_component' => 'إضافة مكون',
    'edit_component' => 'تعديل مكون',
    'component_created' => 'تمت إضافة مكون الراتب بنجاح.',
    'component_updated' => 'تم تحديث مكون الراتب بنجاح.',
    'component_deleted' => 'تم حذف مكون الراتب بنجاح.',
    'payroll' => 'كشف الرواتب',
    'payroll_run' => 'تشغيل الرواتب',
    'payroll_history' => 'سجل الرواتب',
    'select_month' => 'اختر الشهر',
    'run_payroll' => 'تشغيل الرواتب',
    'confirm_payroll' => 'تأكيد الرواتب',
    'payroll_draft' => 'مسودة',
    'payroll_confirmed' => 'مؤكد',
    'payroll_run_created' => 'تم إنشاء دورة الرواتب كمسودة. راجع وأكد.',
    'payroll_confirmed_success' => 'تم تأكيد دورة الرواتب بنجاح.',
    'payroll_duplicate' => 'توجد دورة رواتب مؤكدة لهذا الشهر بالفعل.',
    'payroll_future_month' => 'لا يمكن تشغيل الرواتب لشهر مستقبلي.',
    'no_payroll_runs' => 'لا توجد دورات رواتب بعد.',
    'total_net_payout' => 'إجمالي صافي المدفوعات',
    'employee_count' => 'عدد الموظفين',
    'run_date' => 'تاريخ التشغيل',
    'net_salary' => 'صافي الراتب',
    'total_allowances' => 'إجمالي البدلات',
    'total_deductions' => 'إجمالي الخصومات',
    'payslips' => 'قسائم الرواتب',
    'my_payslips' => 'قسائم رواتبي',
    'payslip_detail' => 'تفاصيل القسيمة',
    'no_payslips' => 'لا توجد قسائم رواتب متاحة.',
    'create_account' => 'إنشاء حساب',
    'create_employee_account' => 'إنشاء حساب دخول للموظف',
    'employee_account_created' => 'تم إنشاء حساب الموظف بنجاح. كلمة المرور: :password',
    'employee_already_has_account' => 'هذا الموظف لديه حساب دخول بالفعل.',
    'employee_email' => 'بريد الموظف الإلكتروني',
    'manage_payroll' => 'إدارة الرواتب',
```

**Checkpoint**: Foundation ready — all 4 tables, 4 models, 3 factories, and bilingual translations are in place.

---

## Phase 3: User Story 1 — Salary Component Management (Priority: P1) 🎯 MVP

**Goal**: Client can add, edit, and delete salary components (allowances/deductions) for each employee.

**Independent Test**: Log in as client → go to employee detail → click "Salary Components" → add Housing allowance 1500 → edit to 2000 → add Insurance deduction 300 → delete it → verify list updates.

### Tests for User Story 1

- [x] T017 [US1] Create the salary component test file at `tests/Feature/Client/SalaryComponentTest.php`:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\SalaryComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalaryComponentTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
        $this->employee = Employee::factory()->create(['client_id' => $this->client->id]);
    }

    public function test_client_can_view_salary_components(): void
    {
        SalaryComponent::factory()->count(3)->create(['employee_id' => $this->employee->id]);

        $response = $this->actingAs($this->clientUser)
            ->get("/client/employees/{$this->employee->id}/salary-components");
        $response->assertStatus(200);
    }

    public function test_client_can_add_salary_component(): void
    {
        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$this->employee->id}/salary-components", [
                'type' => 'allowance',
                'name' => 'Housing',
                'amount' => 1500,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('salary_components', [
            'employee_id' => $this->employee->id,
            'type' => 'allowance',
            'name' => 'Housing',
            'amount' => 1500,
        ]);
    }

    public function test_client_can_update_salary_component(): void
    {
        $component = SalaryComponent::factory()->create([
            'employee_id' => $this->employee->id,
            'amount' => 1500,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->put("/client/employees/{$this->employee->id}/salary-components/{$component->id}", [
                'type' => $component->type,
                'name' => $component->name,
                'amount' => 2000,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('salary_components', [
            'id' => $component->id,
            'amount' => 2000,
        ]);
    }

    public function test_client_can_delete_salary_component(): void
    {
        $component = SalaryComponent::factory()->create([
            'employee_id' => $this->employee->id,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->delete("/client/employees/{$this->employee->id}/salary-components/{$component->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('salary_components', ['id' => $component->id]);
    }

    public function test_client_cannot_access_other_tenant_components(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)
            ->get("/client/employees/{$otherEmployee->id}/salary-components");
        $response->assertStatus(404);
    }

    public function test_salary_component_requires_valid_data(): void
    {
        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$this->employee->id}/salary-components", []);
        $response->assertSessionHasErrors(['type', 'name', 'amount']);
    }
}
```

### Implementation for User Story 1

- [ ] T018 [US1] Create the `SalaryComponentService` at `app/Services/SalaryComponentService.php`:

```php
<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryComponent;

class SalaryComponentService
{
    public function getEmployeeComponents(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)
            ->with('salaryComponents')
            ->findOrFail($employeeId);
    }

    public function create(int $clientId, int $employeeId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        return $employee->salaryComponents()->create($data);
    }

    public function update(int $clientId, int $employeeId, int $componentId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        $component->update($data);
        return $component->fresh();
    }

    public function delete(int $clientId, int $employeeId, int $componentId): bool
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        return $component->delete();
- [x] T018 [US1] Create the `SalaryComponentService` at `app/Services/SalaryComponentService.php`:

```php
<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryComponent;

class SalaryComponentService
{
    public function getEmployeeComponents(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)
            ->with('salaryComponents')
            ->findOrFail($employeeId);
    }

    public function create(int $clientId, int $employeeId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        return $employee->salaryComponents()->create($data);
    }

    public function update(int $clientId, int $employeeId, int $componentId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        $component->update($data);
        return $component->fresh();
    }

    public function delete(int $clientId, int $employeeId, int $componentId): bool
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        return $component->delete();
    }
}
```

- [ ] T019 [US1] Create the `SalaryComponentController` at `app/Http/Controllers/Client/SalaryComponentController.php`:

```php
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
- [x] T019 [US1] Create the `SalaryComponentController` at `app/Http/Controllers/Client/SalaryComponentController.php`:

```php
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
```

- [x] T020 [US1] Add the salary component routes to `routes/client.php`. Insert these lines AFTER the `Route::delete('/employees/{employee}', ...)` line and BEFORE the `// Secure file serving` comment:

```php
    // Salary Components
    Route::get('/employees/{employee}/salary-components', [\App\Http\Controllers\Client\SalaryComponentController::class, 'index'])->name('salary-components.index');
    Route::post('/employees/{employee}/salary-components', [\App\Http\Controllers\Client\SalaryComponentController::class, 'store'])->name('salary-components.store');
    Route::put('/employees/{employee}/salary-components/{component}', [\App\Http\Controllers\Client\SalaryComponentController::class, 'update'])->name('salary-components.update');
    Route::delete('/employees/{employee}/salary-components/{component}', [\App\Http\Controllers\Client\SalaryComponentController::class, 'destroy'])->name('salary-components.destroy');
```

- [x] T021 [US1] Create the salary components Blade view at `resources/views/client/employees/salary-components.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show employee name + position as page header.
  - Display a table of existing salary components with columns: Name, Type (Allowance/Deduction), Amount, Actions (Edit/Delete).
  - Show an inline form at the top to add a new component with fields: Type (select: allowance/deduction), Name (text), Amount (number). Submit button: `{{ __('messages.add_component') }}`.
  - Each existing row has an inline edit form (hidden by default, toggled via Alpine.js `x-show`) with the same fields pre-filled. PUT method via `@method('PUT')`.
  - Each row has a delete button inside a `<form method="POST">@csrf @method('DELETE')</form>`.
  - Show a summary at the bottom: Total Allowances, Total Deductions, Net (Basic + Allowances - Deductions).
  - Include a "Back" link to `/client/employees/{{ $employee->id }}`.
  - Use the existing dark/blue premium CSS theme. All labels use `{{ __('messages.key') }}`.

- [x] T022 [US1] Run the salary component tests: `php artisan test --filter=SalaryComponentTest`. All 6 tests MUST pass.

**Checkpoint**: Salary Component CRUD is functional. Client can manage allowances and deductions per employee.

---

## Phase 4: User Story 2 — Monthly Payroll Run (Priority: P1)

**Goal**: Client runs payroll for a selected month. System generates draft payslips with correct net salary calculations. Client reviews and confirms.

**Independent Test**: Log in as client → go to Payroll → select month → Run Payroll → verify payslips generated → Confirm → verify status changes to confirmed.

### Tests for User Story 2

- [x] T023 [US2] Create the payroll run test file at `tests/Feature/Client/PayrollRunTest.php`:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\PayrollRun;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayrollRunTest extends TestCase
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

    public function test_client_can_view_run_payroll_form(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/client/payroll/run');
        $response->assertStatus(200);
    }

    public function test_client_can_run_payroll(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id, 'basic_salary' => 5000]);
        SalaryComponent::factory()->create(['employee_id' => $employee->id, 'type' => 'allowance', 'name' => 'Housing', 'amount' => 1500]);
        SalaryComponent::factory()->create(['employee_id' => $employee->id, 'type' => 'deduction', 'name' => 'Insurance', 'amount' => 300]);

        $month = now()->startOfMonth()->format('Y-m-d');
        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $month]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_runs', [
            'client_id' => $this->client->id,
            'month' => $month,
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('payslips', [
            'employee_id' => $employee->id,
            'basic_salary' => 5000,
            'total_allowances' => 1500,
            'total_deductions' => 300,
            'net_salary' => 6200,
        ]);
        $this->assertDatabaseHas('payslip_line_items', [
            'component_name' => 'Housing',
            'type' => 'allowance',
            'amount' => 1500,
        ]);
    }

    public function test_client_can_confirm_draft_run(): void
    {
        $run = PayrollRun::factory()->create(['client_id' => $this->client->id, 'status' => 'draft']);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/payroll/{$run->id}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_runs', [
            'id' => $run->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_duplicate_confirmed_run_prevented(): void
    {
        $month = now()->startOfMonth()->format('Y-m-d');
        PayrollRun::factory()->confirmed()->create([
            'client_id' => $this->client->id,
            'month' => $month,
        ]);
        Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $month]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_future_month_prevented(): void
    {
        Employee::factory()->create(['client_id' => $this->client->id]);
        $futureMonth = now()->addMonths(2)->startOfMonth()->format('Y-m-d');

        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $futureMonth]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_client_cannot_access_other_tenant_payroll(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $run = PayrollRun::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)->get("/client/payroll/{$run->id}");
        $response->assertStatus(404);
    }
}
```

### Implementation for User Story 2

- [x] T024 [US2] Create the `PayrollService` at `app/Services/PayrollService.php`:

```php
<?php

namespace App\Services;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLineItem;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollService
{
    public function getHistory(int $clientId)
    {
        return PayrollRun::where('client_id', $clientId)
            ->withCount('payslips')
            ->orderByDesc('month')
            ->paginate(15);
    }

    public function findRun(int $clientId, int $runId): PayrollRun
    {
        return PayrollRun::where('client_id', $clientId)
            ->with(['payslips.employee', 'payslips.lineItems'])
            ->findOrFail($runId);
    }

    public function runPayroll(int $clientId, string $month): PayrollRun
    {
        $monthDate = Carbon::parse($month)->startOfMonth();

        // Prevent future months
        if ($monthDate->isAfter(now()->startOfMonth())) {
            throw new \InvalidArgumentException(__('messages.payroll_future_month'));
        }

        // Prevent duplicate confirmed runs
        $existing = PayrollRun::where('client_id', $clientId)
            ->where('month', $monthDate->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->exists();

        if ($existing) {
            throw new \InvalidArgumentException(__('messages.payroll_duplicate'));
        }

        return DB::transaction(function () use ($clientId, $monthDate) {
            $run = PayrollRun::create([
                'client_id' => $clientId,
                'month' => $monthDate->format('Y-m-d'),
                'status' => 'draft',
            ]);

            $employees = Employee::where('client_id', $clientId)
                ->with('salaryComponents')
                ->get();

            foreach ($employees as $employee) {
                $allowances = $employee->salaryComponents->where('type', 'allowance');
                $deductions = $employee->salaryComponents->where('type', 'deduction');

                $totalAllowances = $allowances->sum('amount');
                $totalDeductions = $deductions->sum('amount');
                $basicSalary = (float) $employee->basic_salary;
                $netSalary = $basicSalary + $totalAllowances - $totalDeductions;

                $payslip = Payslip::create([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $employee->id,
                    'basic_salary' => $basicSalary,
                    'total_allowances' => $totalAllowances,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                ]);

                // Snapshot each component as a line item
                foreach ($employee->salaryComponents as $component) {
                    PayslipLineItem::create([
                        'payslip_id' => $payslip->id,
                        'component_name' => $component->name,
                        'type' => $component->type,
                        'amount' => $component->amount,
                    ]);
                }
            }

            return $run;
        });
    }

    public function confirmRun(int $clientId, int $runId): PayrollRun
    {
        $run = PayrollRun::where('client_id', $clientId)->findOrFail($runId);

        if ($run->isConfirmed()) {
            throw new \InvalidArgumentException('Payroll run is already confirmed.');
        }

        $run->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return $run->fresh();
    }
}
```

- [x] T025 [US2] Create the `PayrollController` at `app/Http/Controllers/Client/PayrollController.php`:

```php
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
}
```

- [x] T026 [US2] Add the payroll routes to `routes/client.php`. Insert these lines AFTER the salary component routes added in T020 and BEFORE `// Secure file serving`:

```php
    // Payroll
    Route::get('/payroll', [\App\Http\Controllers\Client\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/run', [\App\Http\Controllers\Client\PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll/run', [\App\Http\Controllers\Client\PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollRun}', [\App\Http\Controllers\Client\PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payrollRun}/confirm', [\App\Http\Controllers\Client\PayrollController::class, 'confirm'])->name('payroll.confirm');
```

- [x] T027 [US2] Create the payroll "run" form view at `resources/views/client/payroll/run.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show a `<form method="POST" action="{{ route('client.payroll.store') }}">@csrf`.
  - Include a month input `<input type="month" name="month">` with label `{{ __('messages.select_month') }}`.
  - Include a submit button `{{ __('messages.run_payroll') }}`.
  - Display any `error` flash message in a red banner.
  - Display any validation errors.
  - Use the existing dark/blue premium CSS theme. All labels use `{{ __('messages.key') }}`.

- [x] T028 [US2] Create the payroll "show" (detail) view at `resources/views/client/payroll/show.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show a header with month formatted as "March 2026" and status badge (draft=yellow, confirmed=green).
  - If `$run->isDraft()`, show a confirm button inside `<form method="POST" action="{{ route('client.payroll.confirm', $run->id) }}">@csrf</form>` with text `{{ __('messages.confirm_payroll') }}`.
  - Display a table of all payslips: Employee Name, Basic Salary, Total Allowances, Total Deductions, Net Salary.
  - Show a summary row at the bottom totaling all net salaries.
  - Include a "Back" link to `/client/payroll`.
  - Use the existing dark/blue premium CSS theme. All labels use `{{ __('messages.key') }}`.

- [x] T029 [US2] Run the payroll run tests: `php artisan test --filter=PayrollRunTest`. All 6 tests MUST pass.

**Checkpoint**: Payroll Run is functional. Client can generate draft payslips and confirm them.

---

## Phase 5: User Story 3 — Payroll History & Review (Priority: P2)

**Goal**: Client can view all past payroll runs and drill into any run to see individual payslips.

**Independent Test**: Run payroll for multiple months → go to Payroll History → see list of runs → click one → see payslip details.

### Implementation for User Story 3

Note: The `PayrollController@index` and `PayrollController@show` methods were already created in T025 as part of US2. This phase creates the remaining Blade views.

- [x] T030 [US3] Create the payroll history (index) view at `resources/views/client/payroll/index.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show a page header: `{{ __('messages.payroll_history') }}`.
  - Include a "Run Payroll" button linking to `{{ route('client.payroll.create') }}`.
  - Display a table of payroll runs with columns: Month (formatted as "Mar 2026"), Status (badge), Employee Count (`$run->payslips_count`), Total Net Payout (sum of payslips), Run Date.
  - Each row links to `{{ route('client.payroll.show', $run->id) }}`.
  - If no runs exist, show `{{ __('messages.no_payroll_runs') }}`.
  - Paginate results using `{{ $runs->links() }}`.
  - Use the existing dark/blue premium CSS theme. All labels use `{{ __('messages.key') }}`.

**Checkpoint**: Payroll history is browsable. Client can audit past runs.

---

## Phase 6: User Story 4 — Employee Payslip Viewing (Priority: P2)

**Goal**: Employee logs in and views their own payslips with itemized salary breakdown. Client can create employee login accounts.

**Independent Test**: Client creates employee account → Employee logs in → navigates to My Payslips → sees payslip detail with itemized allowances and deductions.

### Tests for User Story 4

- [x] T031 [US4] Create test file at `tests/Feature/Client/EmployeeAccountTest.php`:

```php
<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAccountTest extends TestCase
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

    public function test_client_can_create_employee_account(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id, 'user_id' => null]);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$employee->id}/create-account", [
                'email' => 'emp@test.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'emp@test.com',
            'role' => 'employee',
        ]);
        $this->assertNotNull($employee->fresh()->user_id);
    }

    public function test_cannot_create_duplicate_account(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$employee->id}/create-account", [
                'email' => 'new@test.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
```

- [x] T032 [US4] Create test file at `tests/Feature/Employee/PayslipTest.php`:

```php
<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLineItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayslipTest extends TestCase
{
    use RefreshDatabase;

    private User $employeeUser;
    private Employee $employee;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->employeeUser = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->employeeUser->id,
        ]);
    }

    public function test_employee_can_view_payslip_list(): void
    {
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $this->employee->id,
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/payslips');
        $response->assertStatus(200);
    }

    public function test_employee_can_view_own_payslip_detail(): void
    {
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        $payslip = Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $this->employee->id,
        ]);
        PayslipLineItem::create([
            'payslip_id' => $payslip->id,
            'component_name' => 'Housing',
            'type' => 'allowance',
            'amount' => 1500,
        ]);

        $response = $this->actingAs($this->employeeUser)->get("/employee/payslips/{$payslip->id}");
        $response->assertStatus(200);
        $response->assertSee('Housing');
        $response->assertSee('1500');
    }

    public function test_employee_cannot_view_other_payslip(): void
    {
        $otherEmployee = Employee::factory()->create(['client_id' => $this->client->id]);
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        $payslip = Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $otherEmployee->id,
        ]);

        $response = $this->actingAs($this->employeeUser)->get("/employee/payslips/{$payslip->id}");
        $response->assertStatus(404);
    }
}
```

### Implementation for User Story 4

- [x] T033 [US4] Create the `EmployeeAccountController` at `app/Http/Controllers/Client/EmployeeAccountController.php`:

```php
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountController extends Controller
{
    public function create(int $employee)
    {
        $clientId = auth()->user()->client->id;
        $emp = Employee::where('client_id', $clientId)->findOrFail($employee);
        return view('client.employees.create-account', ['employee' => $emp]);
    }

    public function store(Request $request, int $employee)
    {
        $clientId = auth()->user()->client->id;
        $emp = Employee::where('client_id', $clientId)->findOrFail($employee);

        if ($emp->user_id) {
            return redirect()
                ->route('client.employees.show', $emp->id)
                ->with('error', __('messages.employee_already_has_account'));
        }

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $emp->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'employee',
        ]);

        $emp->update(['user_id' => $user->id]);

        return redirect()
            ->route('client.employees.show', $emp->id)
            ->with('success', __('messages.employee_account_created', ['password' => $password]));
    }
}
```

- [x] T034 [US4] Create the `PayslipController` for employees at `app/Http/Controllers/Employee/PayslipController.php`:

```php
<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payslip;

class PayslipController extends Controller
{
    private function getEmployeeId(): int
    {
        $employee = auth()->user()->employee;
        abort_unless($employee, 404);
        return $employee->id;
    }

    public function index()
    {
        $employeeId = $this->getEmployeeId();
        $payslips = Payslip::where('employee_id', $employeeId)
            ->whereHas('payrollRun', fn($q) => $q->where('status', 'confirmed'))
            ->with('payrollRun')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('employee.payslips.index', compact('payslips'));
    }

    public function show(int $payslip)
    {
        $employeeId = $this->getEmployeeId();
        $payslip = Payslip::where('employee_id', $employeeId)
            ->with(['payrollRun', 'lineItems', 'employee'])
            ->findOrFail($payslip);

        return view('employee.payslips.show', compact('payslip'));
    }
}
```

- [x] T035 [US4] Add the `employee()` relationship to the `User` model at `app/Models/User.php`. Add this method inside the class (if it does not already exist):

```php
    public function employee()
    {
        return $this->hasOne(\App\Models\Employee::class);
    }
```

- [x] T036 [US4] Add the employee account creation routes to `routes/client.php`. Insert these lines AFTER the payroll routes added in T026 and BEFORE `// Secure file serving`:

```php
    // Employee Account Creation
    Route::get('/employees/{employee}/create-account', [\App\Http\Controllers\Client\EmployeeAccountController::class, 'create'])->name('employees.create-account');
    Route::post('/employees/{employee}/create-account', [\App\Http\Controllers\Client\EmployeeAccountController::class, 'store'])->name('employees.store-account');
```

- [x] T037 [US4] Update `routes/employee.php` to replace the placeholder routes with real payslip routes:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('dashboard');

    Route::get('/payslips', [\App\Http\Controllers\Employee\PayslipController::class, 'index'])->name('payslips.index');
    Route::get('/payslips/{payslip}', [\App\Http\Controllers\Employee\PayslipController::class, 'show'])->name('payslips.show');
});
```

- [x] T038 [US4] Create the employee account creation form view at `resources/views/client/employees/create-account.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show employee name as header: "Create Login for {{ $employee->name }}".
  - If `$employee->user_id` is set, show a warning message `{{ __('messages.employee_already_has_account') }}` and no form.
  - Otherwise show a `<form method="POST" action="{{ route('client.employees.store-account', $employee->id) }}">@csrf` with an email field and submit button `{{ __('messages.create_account') }}`.
  - Display validation errors. Use the existing dark/blue premium CSS theme.

- [x] T039 [US4] Create the employee payslip list view at `resources/views/employee/payslips/index.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show header `{{ __('messages.my_payslips') }}`.
  - Display a table: Month (from `$payslip->payrollRun->month`), Basic Salary, Net Salary, View link.
  - Each row links to `{{ route('employee.payslips.show', $payslip->id) }}`.
  - If no payslips, show `{{ __('messages.no_payslips') }}`.
  - Paginate. Use the dark/blue theme.

- [x] T040 [US4] Create the employee payslip detail view at `resources/views/employee/payslips/show.blade.php`. This view must:
  - Extend `@extends('layouts.app')`.
  - Show header: `{{ __('messages.payslip_detail') }}` with month formatted.
  - Show employee name + position.
  - Show Basic Salary as a row.
  - Show a section "Allowances" listing each line item where `type=allowance` with name and amount.
  - Show a section "Deductions" listing each line item where `type=deduction` with name and amount.
  - Show summary: Total Allowances, Total Deductions, **Net Salary** (bold, large).
  - Include a "Back" link. Use the dark/blue theme.

- [x] T041 [US4] Run employee account tests: `php artisan test --filter=EmployeeAccountTest`. All 2 tests MUST pass.

- [x] T042 [US4] Run employee payslip tests: `php artisan test --filter=PayslipTest`. All 3 tests MUST pass.

**Checkpoint**: Employee accounts can be created and employees can view their own payslips. Full self-service is operational.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final integration, full test run, navigation updates, and cleanup.

- [x] T043 [P] Update the client dashboard view at `resources/views/client/dashboard.blade.php` to add a "Manage Payroll" quick-action link/button pointing to `/client/payroll`. Add it next to the existing "Manage Employees" button.

- [x] T044 [P] Update the employee show view at `resources/views/client/employees/show.blade.php` to add two new action buttons:
  - "Salary Components" linking to `{{ route('client.salary-components.index', $employee->id) }}`.
  - "Create Account" linking to `{{ route('client.employees.create-account', $employee->id) }}` (only show if `$employee->user_id` is null).

- [x] T045 Run the FULL test suite: `php artisan test`. ALL tests from Phase 1, Phase 2, AND Phase 3 must pass. Expected tests include:
  - `SalaryComponentTest` (6 tests)
  - `PayrollRunTest` (6 tests)
  - `EmployeeAccountTest` (2 tests)
  - `PayslipTest` (3 tests)
  - All existing Phase 1 and Phase 2 tests

  Fix any failures. Do NOT modify test files — fix the source code instead.

- [x] T046 [P] Review all new Blade views for consistent styling: dark/blue theme, RTL support via `dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"` on the `<html>` tag, responsive layout on mobile screens, proper use of `{{ __('messages.key') }}` for every visible string.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — create tables first
- **Foundational (Phase 2)**: Depends on Phase 1 — BLOCKS all user stories
- **US1 (Phase 3)**: Depends on Phase 2 — MVP deliverable (salary components)
- **US2 (Phase 4)**: Depends on Phase 2 + US1 (salary components must exist for payroll calculation)
- **US3 (Phase 5)**: Depends on Phase 4 (needs payroll runs to display history)
- **US4 (Phase 6)**: Depends on Phase 4 (needs payslips to exist)
- **Polish (Phase 7)**: Depends on all phases

### Within Each User Story

1. Tests MUST be written and FAIL before implementation
2. Models before services
3. Services before controllers
4. Controllers before views
5. Core implementation before integration
6. Final test run to verify

### Parallel Opportunities

- T006, T007, T008, T009 (all 4 models) can run in parallel
- T012, T013, T014 (all 3 factories) can run in parallel
- T015, T016 (translation files) can run in parallel
- T043, T044, T046 (polish tasks) can run in parallel

---

## Implementation Strategy

### MVP First (User Story 1 + 2)

1. Complete Phase 1: Setup (T001-T005)
2. Complete Phase 2: Foundation (T006-T016)
3. Complete Phase 3: Salary Components (T017-T022)
4. Complete Phase 4: Payroll Run (T023-T029)
5. **STOP and VALIDATE**: `php artisan test --filter=SalaryComponentTest && php artisan test --filter=PayrollRunTest`
6. Deploy/demo if ready

### Full Delivery

1. Setup → Foundation → US1 → US2 → US3 → US4 → Polish
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
