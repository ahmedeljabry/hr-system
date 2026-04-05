# Tasks: Super Admin Dashboard

**Input**: Plan from `/specs/007-super-admin-dashboard/plan.md`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, quickstart.md

**Tests**: TDD is mandatory per Constitution Principle II. Feature tests will be created for each user story.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

**LLM Implementation Notes**: Each task below contains exact file paths, method signatures, and code patterns from the existing codebase. Follow the patterns in existing controllers (e.g., `app/Http/Controllers/Employee/TaskController.php`) and services (e.g., `app/Services/AssetService.php`). All views must use `@extends('layouts.admin')` (the new sidebar layout) and `__('key')` for all visible text.

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Localisation keys and admin layout needed by ALL user stories.

- [ ] T001 Add all Phase 7 English localisation keys to `lang/en.json`. Add these keys (one per line):
  ```json
  "Total Clients": "Total Clients",
  "Total Employees": "Total Employees",
  "Active Subscriptions": "Active Subscriptions",
  "Suspended Subscriptions": "Suspended Subscriptions",
  "Expired Subscriptions": "Expired Subscriptions",
  "Company Name": "Company Name",
  "Subscription Status": "Subscription Status",
  "Subscription End": "Subscription End",
  "Employees": "Employees",
  "Active": "Active",
  "Suspended": "Suspended",
  "Expired": "Expired",
  "Has Login": "Has Login",
  "No Login": "No Login",
  "No clients found.": "No clients found.",
  "No employees found.": "No employees found.",
  "Edit User": "Edit User",
  "User updated successfully.": "User updated successfully.",
  "Subscription status updated successfully.": "Subscription status updated successfully.",
  "View Employees": "View Employees",
  "Back to Clients": "Back to Clients",
  "Clients": "Clients",
  "Dashboard": "Dashboard"
  ```

- [ ] T002 Add all Phase 7 Arabic localisation keys to `lang/ar.json`. Add Arabic translations for every key added in T001. Use these translations:
  ```json
  "Total Clients": "إجمالي العملاء",
  "Total Employees": "إجمالي الموظفين",
  "Active Subscriptions": "اشتراكات نشطة",
  "Suspended Subscriptions": "اشتراكات معلقة",
  "Expired Subscriptions": "اشتراكات منتهية",
  "Company Name": "اسم الشركة",
  "Subscription Status": "حالة الاشتراك",
  "Subscription End": "تاريخ انتهاء الاشتراك",
  "Employees": "الموظفين",
  "Active": "نشط",
  "Suspended": "معلق",
  "Expired": "منتهي",
  "Has Login": "لديه حساب",
  "No Login": "لا يوجد حساب",
  "No clients found.": "لا يوجد عملاء.",
  "No employees found.": "لا يوجد موظفون.",
  "Edit User": "تعديل المستخدم",
  "User updated successfully.": "تم تحديث المستخدم بنجاح.",
  "Subscription status updated successfully.": "تم تحديث حالة الاشتراك بنجاح.",
  "View Employees": "عرض الموظفين",
  "Back to Clients": "العودة إلى العملاء",
  "Clients": "العملاء",
  "Dashboard": "لوحة التحكم"
  ```

- [ ] T003 Create the admin sidebar layout file at `resources/views/layouts/admin.blade.php`. This layout MUST:
  1. Copy the `<head>` section from `resources/views/layouts/app.blade.php` (fonts, Alpine.js, Tailwind CDN, CSRF meta).
  2. Use `<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex">` (flex row, not column).
  3. Create a left sidebar `<aside>` with classes `w-64 bg-white border-r sticky top-0 h-screen overflow-y-auto flex-shrink-0` containing:
     - App branding/logo at the top (link to `/admin/dashboard`).
     - Two navigation links as `<a>` tags with icons (use simple emoji or SVG) and text using `__()`:
       - Dashboard → `/admin/dashboard` (📊 icon)
       - Clients → `/admin/clients` (👥 icon)
     - Highlight the active link using `request()->is('admin/dashboard*')` with a blue/indigo background.
     - Language switcher at the bottom of the sidebar (copy from `layouts/app.blade.php`).
     - Logout form at the bottom.
  4. Create a `<main class="flex-1 ml-64 p-6">` area with `@yield('content')`.
  5. Support RTL: when `app()->getLocale() == 'ar'`, sidebar should be on the right side (use `dir="rtl"`, `mr-64` instead of `ml-64`).
  6. Include responsive behavior: on mobile, sidebar collapses behind a hamburger menu using Alpine.js `x-data="{ open: false }"`.

---

## Phase 2: Foundational (AdminStatsService + UserController)

**Purpose**: Core services and controllers that support multiple user stories.

- [ ] T004 Create `AdminStatsService` at `app/Services/AdminStatsService.php`. The service must have one public method:
  ```php
  public function getStats(): array
  ```
  This method returns an associative array with:
  - `'total_clients'` → `Client::count()` (import `App\Models\Client`)
  - `'total_employees'` → `Employee::count()` (import `App\Models\Employee`)
  - `'active_count'` → `Client::where('status', 'active')->count()`
  - `'suspended_count'` → `Client::where('status', 'suspended')->count()`
  - `'expired_count'` → `Client::where('status', 'expired')->count()`

- [ ] T005 Create `AdminUserService` at `app/Services/AdminUserService.php`. The service must have one public method:
  ```php
  public function updateBasicInfo(User $user, array $data): void
  ```
  This method:
  1. Stores the old values: `$old = ['name' => $user->name, 'email' => $user->email]`
  2. Updates the user: `$user->update(['name' => $data['name'], 'email' => $data['email']])`
  3. Logs the action: `Log::channel('daily')->info('ADMIN_ACTION', ['admin_id' => Auth::id(), 'action' => 'user_edit', 'target' => 'users', 'record_id' => $user->id, 'old' => $old, 'new' => ['name' => $data['name'], 'email' => $data['email']]])` (import `Illuminate\Support\Facades\Log`, `Auth`)

- [ ] T006 Create `UserController` for Admin at `app/Http/Controllers/Admin/UserController.php`. The controller must:
  - Inject `AdminUserService` via constructor: `public function __construct(private AdminUserService $adminUserService) {}`
  - Have an `edit(User $user)` method that returns `view('admin.users.edit', compact('user'))`
  - Have an `update(Request $request, User $user)` method that:
    1. Validates: `['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email,' . $user->id]`
    2. Calls `service->updateBasicInfo($user, $validated)`
    3. Returns `back()->with('success', __('User updated successfully.'))` (import `App\Services\AdminUserService`)

- [ ] T007 Add user routes to `routes/admin.php`. Inside the existing middleware group, add:
  ```php
  Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
  Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
  ```

---

## Phase 3: User Story 1 — Admin Dashboard with Stats Widgets (Priority: P1) 🎯 MVP

**Goal**: Replace the static dashboard with real aggregate stats.

**Independent Test**: Log in as super admin, GET `/admin/dashboard`, assert 5 widget counts match database.

- [ ] T008 Create `DashboardController` for Admin at `app/Http/Controllers/Admin/DashboardController.php`. The controller must:
  - Inject `AdminStatsService` via constructor: `public function __construct(private AdminStatsService $adminStatsService) {}`
  - Have an `index()` method that calls `$stats = $this->adminStatsService->getStats()` and returns `view('admin.dashboard', compact('stats'))`

- [ ] T009 Update `routes/admin.php` to replace the dashboard closure with the new controller. Change line 5-7 from `Route::get('/dashboard', function () { return view('admin.dashboard'); })` to `Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');`.

- [ ] T010 Rewrite `resources/views/admin/dashboard.blade.php` to use the new sidebar layout and display real stats. The view must:
  1. Use `@extends('layouts.admin')` instead of `@extends('layouts.app')`.
  2. Display a welcome header with super admin greeting.
  3. Show a 2x3 grid of stat widgets:
     - **Total Clients** card: Show `$stats['total_clients']`
     - **Total Employees** card: Show `$stats['total_employees']`
     - **Active Subscriptions** card: Show `$stats['active_count']`
     - **Suspended Subscriptions** card: Show `$stats['suspended_count']`
     - **Expired Subscriptions** card: Show `$stats['expired_count']`
     - **Links** card: Links to `/admin/clients` and `/admin/dashboard`
  4. Use Tailwind card styling: `bg-white rounded-2xl shadow-sm p-6 border border-gray-100`
  5. All text must use `__()` helpers. Include empty state handling for zero counts.

---

## Phase 4: User Story 2 — Client List with Enhanced Features (Priority: P2)

**Goal**: Upgrade client list with pagination, sort, employee count, and inline status dropdown.

**Independent Test**: Log in as super admin, GET `/admin/clients`, verify pagination, sort links, employee counts, and status dropdown saves immediately.

- [ ] T011 Create `ClientListTest` at `tests/Feature/Admin/ClientListTest.php`. The test class must:
  - `use RefreshDatabase`.
  - In `setUp()`: create Client (active subscription), create a User with `role=super_admin`
  - Test `test_client_list_shows_paginated_clients()`: Create 20 clients. Act as super admin, GET `/admin/clients`, assert status 200, assert sees "Company Name", assert pagination links exist.
  - Test `test_client_list_includes_employee_count()`: Create 1 client with 3 employees. Act as super admin, GET `/admin/clients`, assert sees "3" in the employee count column.
  - Test `test_status_dropdown_updates_immediately()`: Act as super admin, PATCH `/admin/clients/{id}/status` with `status=suspended`, assert redirect back, assert DB updated, assert sees success flash message.

- [ ] T012 Upgrade `ClientController::index()` at `app/Http/Controllers/Admin/ClientController.php`. The method must:
  - Accept `Request $request` parameter.
  - Define `$sortable = ['name', 'status', 'subscription_end']`
  - Get sort params: `$sort = in_array($request->get('sort'), $sortable) ? $request->get('sort') : 'name'` and `$dir = $request->get('dir') === 'desc' ? 'desc' : 'asc'`
  - Query clients: `$clients = Client::withCount('employees')->orderBy($sort, $dir)->paginate(15)->withQueryString()`
  - Return `view('admin.clients.index', compact('clients', 'sort', 'dir'))`

- [ ] T013 Upgrade `ClientController::updateStatus()` at `app/Http/Controllers/Admin/ClientController.php`. The method must:
  - Add audit logging after successful status change: `Log::channel('daily')->info('ADMIN_ACTION', ['admin_id' => Auth::id(), 'action' => 'status_change', 'target' => 'clients', 'record_id' => $client->id, 'old' => $old, 'new' => $data['status']])`
  - Use `__('Subscription status updated successfully.')` for the flash message (import `Log`)

- [ ] T014 Upgrade `resources/views/admin/clients/index.blade.php` to use the new layout and features. The view must:
  1. Use `@extends('layouts.admin')` instead of `@extends('layouts.app')`.
  2. Add a new "Employees" column header in the table.
  3. Add employee count cell: `<td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 font-bold">{{ $client->employees_count }}</td>`
  4. Replace the action buttons with an inline form containing a `<select>` dropdown:
     ```html
     <form action="{{ route('admin.clients.status', $client->id) }}" method="POST" class="inline">
       @csrf @method('PATCH')
       <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-400">
         <option value="active" {{ $client->status === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
         <option value="suspended" {{ $client->status === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
         <option value="expired" {{ $client->status === 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
       </select>
     </form>
     ```
  5. Add sortable column headers with sort links and direction indicators.
  6. Add pagination: `{{ $clients->links() }}`
  7. Add empty state: if `$clients->isEmpty()`, show `{{ __('No clients found.') }}`
  8. Add "View Employees" link in each row: `<a href="{{ route('admin.clients.show', $client->id) }}" class="text-blue-600 hover:text-blue-800">{{ __('View Employees') }}</a>`

---

## Phase 5: User Story 3 — Client Detail: Employee List (Priority: P3)

**Goal**: Add client detail page showing employee roster.

**Independent Test**: Log in as super admin, navigate to client detail, verify employee list shows only that client's employees.

- [ ] T015 Create `ClientDetailTest` at `tests/Feature/Admin/ClientDetailTest.php`. The test class must:
  - `use RefreshDatabase`.
  - In `setUp()`: create 2 clients, create User with `role=super_admin`
  - Test `test_client_detail_shows_employees_of_correct_client()`: Create 2 employees for Client A, 1 employee for Client B. Act as super admin, GET `/admin/clients/{clientA->id}`, assert sees 2 employee names, does NOT see Client B's employee.

- [ ] T016 Add `show()` method to `ClientController` at `app/Http/Controllers/Admin/ClientController.php`. The method must:
  - Accept `Client $client` parameter
  - Get employees: `$employees = $client->employees()->with('user')->get()`
  - Return `view('admin.clients.show', compact('client', 'employees'))`

- [ ] T017 Add client detail route to `routes/admin.php`. Inside the middleware group, add: `Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');`

- [ ] T018 Create `resources/views/admin/clients/show.blade.php`. The view must:
  1. Use `@extends('layouts.admin')`.
  2. Display client header: company name, subscription status.
  3. Show employee table with columns: Name, Position, Hire Date, Login Status.
  4. For each employee: display `$employee->name`, `$employee->position`, `$employee->hire_date->format('Y-m-d')`, and `$employee->user_id ? __('Has Login') : __('No Login')`.
  5. Add "Back to Clients" link: `<a href="{{ route('admin.clients.index') }}">{{ __('Back to Clients') }}</a>`
  6. Add "Edit User" link for each employee if they have a user account: `<a href="{{ route('admin.users.edit', $employee->user->id) }}">{{ __('Edit User') }}</a>`
  7. Add empty state: if `$employees->isEmpty()`, show `{{ __('No employees found.') }}`

---

## Phase 6: User Story 4 — Edit Any User's Basic Info (Priority: P4)

**Goal**: Enable super admin to edit user name and email.

**Independent Test**: Log in as super admin, edit a user's name and email, verify changes persist and are logged.

- [ ] T019 Create `UserEditTest` at `tests/Feature/Admin/UserEditTest.php`. The test class must:
  - `use RefreshDatabase`.
  - In `setUp()`: create User with `role=super_admin`, create another User to edit
  - Test `test_super_admin_can_edit_user_name_and_email()`: Act as super admin, PATCH `/admin/users/{user->id}` with new name and email, assert redirect back, assert DB updated, assert success flash message.
  - Test `test_email_validation_prevents_duplicates()`: Attempt to set email to an existing user's email, assert validation error.

- [ ] T020 Create `resources/views/admin/users/edit.blade.php`. The view must:
  1. Use `@extends('layouts.admin')`.
  2. Display user header: current name and email.
  3. Show form with fields:
     - Name: `<input type="text" name="name" value="{{ old('name', $user->name) }}" required>`
     - Email: `<input type="email" name="email" value="{{ old('email', $user->email) }}" required>`
  4. Submit via PATCH to `route('admin.users.update', $user->id)`
  5. Include `@csrf` and `@method('PATCH')`
  6. Show validation errors with `@error`
  7. Add "Back to Clients" link

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final validation, route access testing, and cleanup.

- [ ] T021 Create `RouteAccessTest` at `tests/Feature/Admin/RouteAccessTest.php`. The test class must:
  - `use RefreshDatabase`.
  - Test `test_super_admin_can_access_admin_routes()`: Create User with `role=super_admin`, act as that user, GET `/admin/dashboard`, assert status 200.
  - Test `test_non_super_admin_cannot_access_admin_routes()`: Create User with `role=client`, act as that user, GET `/admin/dashboard`, assert status 403.

- [ ] T022 Run `php artisan test --filter=Admin` to execute all admin feature tests. Fix any failures.

- [ ] T023 Verify audit logging works by checking `storage/logs/laravel-*.log` after running a status update or user edit.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: No dependencies — start immediately.
- **Phase 2 (Foundational)**: Depends on Phase 1 — services needed by all stories.
- **Phase 3 (US1 Dashboard)**: Depends on Phase 2 — needs `AdminStatsService`.
- **Phase 4 (US2 Client List)**: Depends on Phase 2 — needs updated `ClientController`.
- **Phase 5 (US3 Client Detail)**: Depends on Phase 4 — builds on client list.
- **Phase 6 (US4 User Edit)**: Depends on Phase 2 — needs `AdminUserService` and `UserController`.
- **Phase 7 (Polish)**: Depends on all user stories being complete.

### Within Each User Story

- Tests MUST be written and FAIL before implementation.
- Services before controllers.
- Controllers before views.
- Routes must be added before views can be tested.

### Parallel Opportunities

- T001 + T002 (localization files are separate)
- T008 + T011 (dashboard controller + test can run in parallel with client list controller + test)
- Phases 3, 4, 5, 6 can run in parallel after Phase 2 if multiple people work on them.

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (T001–T003)
2. Complete Phase 2: Foundational (T004–T007)
3. Complete Phase 3: US1 Dashboard (T008–T010)
4. **STOP and VALIDATE**: Test dashboard independently
5. Deploy/demo if ready

### Incremental Delivery

1. Setup + Foundational → Core services ready
2. Add US1 Dashboard → Stats functional
3. Add US2 Client List → Management functional
4. Add US3 Client Detail → Inspection functional
5. Add US4 User Edit → Full admin suite
6. Polish → Final delivery

---

## Notes

- All new admin views MUST use `@extends('layouts.admin')` (the new sidebar layout), NOT `@extends('layouts.app')`.
- All visible text MUST use `__('key')` for bilingual support.
- The inline status dropdown uses `onchange="this.form.submit()"` for immediate saves without JavaScript complexity.
- Audit logging uses the `daily` log channel and follows the structured format defined in the plan.
- Pagination uses Laravel's built-in `->paginate(15)` with `->withQueryString()` to preserve sort parameters.
- Empty states are handled in views with conditional display of messages when collections are empty.