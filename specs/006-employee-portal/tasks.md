# Tasks: Employee Portal

**Input**: Design documents from `/specs/006-employee-portal/`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, quickstart.md

**Tests**: TDD is mandatory per Constitution Principle II. Feature tests will be created for each user story.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

**LLM Implementation Notes**: Each task below contains exact file paths, method signatures, and code patterns from the existing codebase. Follow the patterns in existing controllers (e.g., `app/Http/Controllers/Employee/TaskController.php`) and services (e.g., `app/Services/AssetService.php`). All views must use `@extends('layouts.employee')` (the new sidebar layout) and `__('key')` for all visible text.

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Subscription enforcement and localization strings needed by ALL user stories.

- [x] T001 Add `check_subscription` middleware to employee route group in `routes/employee.php`. Change line 5 from `Route::middleware(['auth', 'role:employee'])` to `Route::middleware(['auth', 'role:employee', 'check_subscription'])`. This ensures employees of expired-subscription clients are blocked from all portal access (FR-011).

- [x] T002 [P] Add all Phase 6 English localization strings to `lang/en.json`. Add these keys: `"My Profile"`, `"My Leaves"`, `"Announcements"`, `"Pending Tasks"`, `"Assigned Assets"`, `"Latest Payslip"`, `"Leave Balance"`, `"No data available"`, `"Contact your employer"`, `"View All"`, `"Published"`, `"Back"`, `"Create Announcement"`, `"Edit Announcement"`, `"Delete Announcement"`, `"Announcement created successfully."`, `"Announcement updated successfully."`, `"Announcement deleted successfully."`, `"Employee Profile"`, `"Position"`, `"Hire Date"`, `"Basic Salary"`, `"National ID"`, `"Contract"`, `"Document not available"`, `"No announcements yet."`, `"No leave records found."`, `"Net Salary"`.

- [x] T003 [P] Add all Phase 6 Arabic localization strings to `lang/ar.json`. Add Arabic translations for every key added in T002. Examples: `"My Profile": "ملفي الشخصي"`, `"Announcements": "الإعلانات"`, `"Pending Tasks": "المهام المعلقة"`, `"Assigned Assets": "الأصول المخصصة"`, `"Latest Payslip": "آخر كشف راتب"`, `"Leave Balance": "رصيد الإجازات"`, `"Create Announcement": "إنشاء إعلان"`, `"Employee Profile": "ملف الموظف"`, `"Document not available": "المستند غير متاح"`, `"No announcements yet.": "لا توجد إعلانات بعد."`, `"No leave records found.": "لا توجد سجلات إجازات."`, `"Net Salary": "صافي الراتب"`.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: The sidebar layout and Announcement entity that MUST be complete before ANY user story.

**⚠️ CRITICAL**: No user story work can begin until this phase is complete.

- [x] T004 Create the employee sidebar layout file at `resources/views/layouts/employee.blade.php`. This layout MUST:
  1. Copy the `<head>` section from `resources/views/layouts/app.blade.php` (fonts, Alpine.js, Tailwind CDN, CSRF meta).
  2. Use `<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex">` (flex row, not column).
  3. Create a left sidebar `<aside>` (w-64, bg-white, border-r, min-h-screen, fixed or sticky) containing:
     - App branding/logo at the top (link to `/employee/dashboard`).
     - 7 navigation links as `<a>` tags, each with an icon (use simple emoji or SVG) and text label using `__()`:
       - Dashboard → `/employee/dashboard`
       - My Profile → `/employee/profile`
       - Payslips → `/employee/payslips`
       - My Leaves → `/employee/leaves`
       - Tasks → `/employee/tasks`
       - Assets → `/employee/assets`
       - Announcements → `/employee/announcements`
     - Highlight the active link using `request()->is('employee/dashboard*')` with a blue/indigo background.
     - Language switcher at the bottom of the sidebar (copy from `app.blade.php`).
     - Logout form at the bottom.
  4. Create a `<main class="flex-1 ml-64 p-6">` area with `@yield('content')`.
  5. Support RTL: when `app()->getLocale() == 'ar'`, sidebar should be on the right side (use `dir="rtl"`, `mr-64` instead of `ml-64`).
  6. Include responsive behavior: on mobile, sidebar collapses behind a hamburger menu using Alpine.js `x-data="{ open: false }"`.

- [x] T005 Create migration for `announcements` table at `database/migrations/xxxx_create_announcements_table.php`. Run `php artisan make:migration create_announcements_table`. The migration must create table with columns:
  - `id` — `$table->id()`
  - `client_id` — `$table->foreignId('client_id')->constrained()->onDelete('cascade')`
  - `title` — `$table->string('title')`
  - `body` — `$table->text('body')`
  - `published_at` — `$table->timestamp('published_at')->useCurrent()`
  - `timestamps()` — Laravel timestamps
  - Add index: `$table->index(['client_id', 'published_at'])`

- [x] T006 Create `Announcement` model at `app/Models/Announcement.php`. The model must:
  - Use `HasFactory` trait.
  - Set `$fillable = ['client_id', 'title', 'body', 'published_at']`.
  - Set `$casts = ['published_at' => 'datetime']`.
  - Add `belongsTo(Client::class)` relationship method.
  - Add a global scope or use the `BelongsToTenant` trait (check if `app/Models/Traits/BelongsToTenant.php` exists — if it does, use it; if not, add a manual `scopeForClient($query, $clientId)` method).

- [x] T007 [P] Create `AnnouncementFactory` at `database/factories/AnnouncementFactory.php`. The factory must:
  - Import `App\Models\Client` at the top (NOT inside the class).
  - Set `definition()` to return: `'client_id' => Client::factory()`, `'title' => $this->faker->sentence()`, `'body' => $this->faker->paragraph(3)`, `'published_at' => now()`.

- [x] T008 [P] Add `announcements()` relationship to `app/Models/Client.php`. Add this method: `public function announcements() { return $this->hasMany(Announcement::class); }`. Import `App\Models\Announcement` at the top of the file.

- [x] T009 Run `php artisan migrate` to create the `announcements` table in the database.

**Checkpoint**: Foundation ready — sidebar layout exists, Announcement entity is migrated, user story implementation can now begin.

---

## Phase 3: User Story 1 — Employee Dashboard with Summary Widgets (Priority: P1) 🎯 MVP

**Goal**: Replace the placeholder dashboard with real data-driven widgets showing task count, asset count, latest payslip, leave balance, and recent announcements.

**Independent Test**: Log in as employee → see correct widget counts for tasks, assets, payslip, and announcements — all scoped to the logged-in employee only.

### Tests for User Story 1

- [x] T010 [P] [US1] Create `DashboardTest` at `tests/Feature/Employee/DashboardTest.php`. The test class must:
  - `use RefreshDatabase`.
  - In `setUp()`: create a Client (active subscription), create an Employee linked to that Client, create a User with `role=employee` and `client_id` set, and link `employee.user_id`.
  - Test `test_employee_sees_dashboard_with_widgets()`: Create 3 Task records (2 with `status=todo`, 1 with `status=done`) for the employee. Create 2 Asset records for the employee. Act as the employee user, GET `/employee/dashboard`, assert status 200, assert the response contains "2" (pending tasks — not done), assert contains "2" (assets count).
  - Test `test_dashboard_shows_zero_for_new_employee()`: Act as the employee user (no tasks/assets), GET `/employee/dashboard`, assert status 200, assert contains "0".
  - Test `test_employee_cannot_see_other_employees_data()`: Create tasks/assets for a DIFFERENT employee of the SAME client. Act as the first employee, GET `/employee/dashboard`, assert the counts show "0" (not the other employee's data).

### Implementation for User Story 1

- [x] T011 [US1] Create `DashboardService` at `app/Services/DashboardService.php`. The service must have one public method:
  ```php
  public function getWidgetData(\App\Models\Employee $employee): array
  ```
  This method returns an associative array with:
  - `'pending_tasks'` → `Task::where('employee_id', $employee->id)->whereIn('status', ['todo', 'in_progress'])->count()` (import `App\Models\Task`)
  - `'assigned_assets'` → `Asset::where('employee_id', $employee->id)->count()` (import `App\Models\Asset`)
  - `'latest_payslip'` → `Payslip::where('employee_id', $employee->id)->latest()->first()` (import `App\Models\Payslip`) — returns null if no payslips
  - `'recent_announcements'` → `Announcement::where('client_id', $employee->client_id)->latest('published_at')->take(3)->get()` (import `App\Models\Announcement`)
  - `'leave_balance'` → `null` (placeholder until Phase 4 is built)

- [x] T012 [US1] Create `DashboardController` at `app/Http/Controllers/Employee/DashboardController.php`. Follow the exact pattern from `app/Http/Controllers/Employee/TaskController.php`:
  - Inject `DashboardService` via constructor.
  - In `index()`: get `$employee = Auth::user()->employee`. If `!$employee`, redirect to `employee.dashboard` with error. Otherwise call `$this->dashboardService->getWidgetData($employee)` and pass the result to `view('employee.dashboard', compact('widgets'))`.

- [x] T013 [US1] Update `routes/employee.php` to replace the dashboard closure with the new controller. Change line 6-8 from `Route::get('/dashboard', function () { return view('employee.dashboard'); })` to `Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');`.

- [x] T014 [US1] Rewrite `resources/views/employee/dashboard.blade.php` to use the new sidebar layout and display real widgets. The view must:
  1. Use `@extends('layouts.employee')` instead of `@extends('layouts.app')`.
  2. Display a welcome banner with employee name: `{{ Auth::user()->name }}`.
  3. Show a 2x2 or 3-column grid of widget cards:
     - **Pending Tasks** card: Show `{{ $widgets['pending_tasks'] }}` with a link to `/employee/tasks`.
     - **Assigned Assets** card: Show `{{ $widgets['assigned_assets'] }}` with a link to `/employee/assets`.
     - **Latest Payslip** card: If `$widgets['latest_payslip']`, show net salary formatted with `number_format()`. Otherwise show `{{ __('No data available') }}`.
     - **Leave Balance** card: Show `{{ __('No data available') }}` (placeholder for Phase 4).
  4. Show a "Recent Announcements" section below the widgets: loop `$widgets['recent_announcements']` and display each announcement title and truncated body. If empty, show `{{ __('No announcements yet.') }}`.
  5. Use Tailwind classes: `bg-white rounded-2xl shadow-sm p-6 border border-gray-100` for cards. Use gradient headers for the welcome banner. All text must use `__()` for localization.

**Checkpoint**: Dashboard is functional with real data. Employees see their own counts only.

---

## Phase 4: User Story 2 — Employee Profile View (Priority: P2)

**Goal**: Employees view their own personal info and securely access uploaded documents (national ID, contract).

**Independent Test**: Log in as employee → navigate to `/employee/profile` → see name, position, hire date, salary. Click document links → view images served securely.

### Tests for User Story 2

- [x] T015 [P] [US2] Create `ProfileTest` at `tests/Feature/Employee/ProfileTest.php`. The test must:
  - `use RefreshDatabase`.
  - In `setUp()`: create Client, Employee (with `name`, `position`, `hire_date`, `basic_salary`), and User linked to them.
  - Test `test_employee_can_view_own_profile()`: Act as employee, GET `/employee/profile`, assert 200, assert sees employee name, position.
  - Test `test_profile_shows_document_not_available_when_no_files()`: Employee has `national_id_image = null` and `contract_image = null`. Act as employee, GET `/employee/profile`, assert sees `__('Document not available')` text.

### Implementation for User Story 2

- [x] T016 [US2] Create `ProfileController` at `app/Http/Controllers/Employee/ProfileController.php`. The controller must:
  - Have an `index()` method that gets `$employee = Auth::user()->employee`, checks it exists (redirect if not), and returns `view('employee.profile.index', compact('employee'))`.
  - Have a `document($type)` method that:
    1. Gets the employee record.
    2. Validates `$type` is either `national_id` or `contract`.
    3. Gets the file path: if `$type == 'national_id'`, use `$employee->national_id_image`; if `contract`, use `$employee->contract_image`.
    4. If the file path is null or the file doesn't exist in storage, abort(404).
    5. Return `response()->file(storage_path('app/private/' . $filePath))`.

- [x] T017 [US2] Add profile routes to `routes/employee.php`. Inside the existing middleware group, add:
  ```php
  Route::get('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'index'])->name('profile.index');
  Route::get('/profile/documents/{type}', [\App\Http\Controllers\Employee\ProfileController::class, 'document'])->name('profile.document')->where('type', 'national_id|contract');
  ```

- [x] T018 [US2] Create `resources/views/employee/profile/index.blade.php`. The view must:
  1. Use `@extends('layouts.employee')`.
  2. Display a profile card with the employee's: `name`, `position`, `hire_date->format('Y-m-d')`, `number_format(basic_salary, 2)`.
  3. Show two document sections (National ID and Contract):
     - If `$employee->national_id_image` exists: show an `<img>` tag with `src="{{ route('employee.profile.document', 'national_id') }}"` and a "View" link.
     - If null: show `{{ __('Document not available') }}` with a placeholder icon.
     - Same pattern for `contract_image`.
  4. All text must use `__()` helpers. Use Tailwind card styling consistent with the dashboard.

**Checkpoint**: Profile page is functional. Employees see their own info and documents securely.

---

## Phase 5: User Story 3 — Company Announcements (Priority: P3)

**Goal**: Clients create/edit/delete announcements. Employees see a paginated, chronological feed scoped to their company.

**Independent Test**: Client creates announcement → employee of same client sees it → employee of different client does NOT see it.

### Tests for User Story 3

- [x] T019 [P] [US3] Create `AnnouncementTest` at `tests/Feature/Client/AnnouncementTest.php`. The test must:
  - `use RefreshDatabase`.
  - Set up a Client with active subscription and a linked User (`role=client`).
  - Test `test_client_can_create_announcement()`: POST to `/client/announcements` with `title` and `body`, assert redirect, assert `assertDatabaseHas('announcements', ['title' => ..., 'client_id' => ...])`.
  - Test `test_client_can_view_announcements_index()`: Create 2 announcements for the client. GET `/client/announcements`, assert 200, assert sees both titles.
  - Test `test_client_can_delete_announcement()`: Create an announcement. DELETE `/client/announcements/{id}`, assert redirect, assert `assertDatabaseMissing`.

- [x] T020 [P] [US3] Create `AnnouncementVisibilityTest` at `tests/Feature/Employee/AnnouncementVisibilityTest.php`. The test must:
  - Set up two clients (A and B), each with an employee user.
  - Create announcements for Client A.
  - Test `test_employee_sees_own_client_announcements()`: Act as employee of Client A, GET `/employee/announcements`, assert 200, assert sees the announcements.
  - Test `test_employee_cannot_see_other_client_announcements()`: Act as employee of Client B, GET `/employee/announcements`, assert does NOT see Client A's announcements.

### Implementation for User Story 3

- [x] T021 [US3] Create `AnnouncementService` at `app/Services/AnnouncementService.php`. The service must have methods:
  - `getForClient(Client $client, int $perPage = 10)` → returns `$client->announcements()->latest('published_at')->paginate($perPage)`.
  - `create(Client $client, array $data)` → returns `$client->announcements()->create($data)`.
  - `update(Announcement $announcement, array $data)` → calls `$announcement->update($data)` and returns the result.
  - `delete(Announcement $announcement)` → calls `$announcement->delete()`.
  - `getForEmployee(Employee $employee, int $perPage = 10)` → returns `Announcement::where('client_id', $employee->client_id)->latest('published_at')->paginate($perPage)`.
  Import `App\Models\Announcement`, `App\Models\Client`, `App\Models\Employee`.

- [x] T022 [US3] Create `AnnouncementController` for Client at `app/Http/Controllers/Client/AnnouncementController.php`. Follow the exact CRUD pattern from `app/Http/Controllers/Client/AssetController.php`:
  - Inject `AnnouncementService` via constructor.
  - `index()`: call service `getForClient(Auth::user()->client)`, return `view('client.announcements.index', compact('announcements'))`.
  - `create()`: return `view('client.announcements.create')`.
  - `store(Request $request)`: validate `title` (required, string, max:255) and `body` (required, string, max:5000). Call `service->create(Auth::user()->client, $validated)`. Redirect to `client.announcements.index` with success message.
  - `edit(Announcement $announcement)`: return `view('client.announcements.edit', compact('announcement'))`.
  - `update(Request $request, Announcement $announcement)`: validate same as store. Call `service->update($announcement, $validated)`. Redirect with success.
  - `destroy(Announcement $announcement)`: call `service->delete($announcement)`. Redirect with success.

- [x] T023 [US3] Create `AnnouncementController` for Employee at `app/Http/Controllers/Employee/AnnouncementController.php`. Follow the pattern from `app/Http/Controllers/Employee/TaskController.php`:
  - Inject `AnnouncementService` via constructor.
  - `index()`: get employee via `Auth::user()->employee`. Call `service->getForEmployee($employee)`. Return `view('employee.announcements.index', compact('announcements'))`.

- [x] T024 [US3] Add announcement routes. In `routes/client.php`, inside the existing middleware group (before the closing `});`), add: `Route::resource('announcements', \App\Http\Controllers\Client\AnnouncementController::class);`. In `routes/employee.php`, add: `Route::get('/announcements', [\App\Http\Controllers\Employee\AnnouncementController::class, 'index'])->name('announcements.index');`.

- [x] T025 [US3] Create `resources/views/client/announcements/index.blade.php`. Use `@extends('layouts.app')`. Display a table of announcements with columns: Title, Published Date, Actions (Edit/Delete). Include a "Create Announcement" button linking to the create page. Use `$announcements->links()` for pagination. Add CSRF-protected delete forms with confirmation. All text via `__()`.

- [x] T026 [US3] Create `resources/views/client/announcements/create.blade.php`. Use `@extends('layouts.app')`. Show a form with fields: `title` (text input, required), `body` (textarea, required, placeholder text). Submit via POST to `route('client.announcements.store')`. Include `@csrf`. Show `@error` validation messages. All text via `__()`.

- [x] T027 [US3] Create `resources/views/client/announcements/edit.blade.php`. Use `@extends('layouts.app')`. Same as create but pre-filled with `$announcement->title` and `$announcement->body`. Submit via PUT to `route('client.announcements.update', $announcement)`. Include `@csrf` and `@method('PUT')`.

- [x] T028 [US3] Create `resources/views/employee/announcements/index.blade.php`. Use `@extends('layouts.employee')`. Display a list/feed of announcements showing: title (bold), published date, and body text (display with `nl2br(e($announcement->body))` to preserve line breaks safely). Use `$announcements->links()` for pagination. If empty, show `{{ __('No announcements yet.') }}`. All text via `__()`.

**Checkpoint**: Announcements CRUD works for clients. Employees see only their company's announcements.

---

## Phase 6: User Story 4 — Leave Balance & History Scaffold (Priority: P4)

**Goal**: Scaffold the leave balance and history page with an empty state that's ready for Phase 4 integration.

**Independent Test**: Log in as employee → navigate to `/employee/leaves` → see a friendly empty state message.

### Implementation for User Story 4

- [x] T029 [US4] Create `LeaveController` at `app/Http/Controllers/Employee/LeaveController.php`. The controller must have an `index()` method that simply returns `view('employee.leaves.index')`. No service injection needed yet — this is a scaffold.

- [x] T030 [US4] Add leave route to `routes/employee.php`. Inside the middleware group, add: `Route::get('/leaves', [\App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('leaves.index');`.

- [x] T031 [US4] Create `resources/views/employee/leaves/index.blade.php`. Use `@extends('layouts.employee')`. Display a centered empty state with an icon, heading `{{ __('My Leaves') }}`, and message `{{ __('No leave records found.') }}`. Add a subtle note: "This feature will be available soon." Style with Tailwind: `text-gray-400`, `border-dashed`, etc.

**Checkpoint**: Leave page exists with empty state. Ready for Phase 4 data integration later.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Update existing employee views to use the new sidebar layout and final validation.

- [x] T032 Update ALL existing employee views to use the new sidebar layout. Change `@extends('layouts.app')` to `@extends('layouts.employee')` in these files:
  - `resources/views/employee/tasks/index.blade.php`
  - `resources/views/employee/assets/index.blade.php`
  - `resources/views/employee/payslips/index.blade.php`
  - `resources/views/employee/payslips/show.blade.php`

- [x] T033 [P] Update `resources/views/layouts/app.blade.php` employee navigation section (lines 57-61). Since employees now use the sidebar layout, the top-nav links for employees are no longer needed. Replace the `@elseif(Auth::user()->isEmployee())` block to only show a single link to `/employee/dashboard` (employees will use the sidebar for the rest).

- [x] T034 [P] Verify all Arabic translations in `lang/ar.json` are complete for Phase 6. Open the file, search for any keys added in T002 that are missing Arabic values, and add them.

- [x] T035 Run `php artisan test` to execute all feature tests including DashboardTest, ProfileTest, AnnouncementTest, and AnnouncementVisibilityTest. Fix any failures before marking complete.

- [x] T036 Verify zero cross-tenant leakage by running `php artisan test --filter=AnnouncementVisibilityTest` and confirming no employee can see announcements from another company.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: No dependencies — start immediately.
- **Phase 2 (Foundational)**: Depends on Phase 1 — BLOCKS all user stories.
- **Phase 3 (US1 Dashboard)**: Depends on Phase 2. This is the MVP.
- **Phase 4 (US2 Profile)**: Depends on Phase 2. Independent of US1.
- **Phase 5 (US3 Announcements)**: Depends on Phase 2 (Announcement entity). Independent of US1/US2.
- **Phase 6 (US4 Leaves)**: Depends on Phase 2. Independent of all other stories.
- **Phase 7 (Polish)**: Depends on all user stories being complete.

### Within Each User Story

- Tests MUST be written and FAIL before implementation.
- Services before controllers.
- Controllers before views.
- Routes must be added before views can be tested.

### Parallel Opportunities

- T002 + T003 (localization files are separate)
- T007 + T008 (factory and relationship are separate files)
- T010 + T015 + T019 + T020 (all test files are independent)
- Phases 3, 4, 5, 6 can run in parallel after Phase 2 if multiple people work on them.

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (T001–T003)
2. Complete Phase 2: Foundational (T004–T009)
3. Complete Phase 3: US1 Dashboard (T010–T014)
4. **STOP and VALIDATE**: Test dashboard independently
5. Deploy/demo if ready

### Incremental Delivery

1. Setup + Foundational → Foundation ready
2. Add US1 Dashboard → Test → MVP ready
3. Add US2 Profile → Test → Self-service ready
4. Add US3 Announcements → Test → Communication ready
5. Add US4 Leaves → Test → Full scaffold ready
6. Polish → Final delivery

---

## Notes

- All views MUST use `@extends('layouts.employee')` (the new sidebar layout), NOT `@extends('layouts.app')`.
- All visible text MUST use `__('key')` for bilingual support.
- The `AnnouncementController` for Client uses `@extends('layouts.app')` (the existing top-nav layout — clients don't get the employee sidebar).
- The `nl2br(e($text))` pattern is used for announcement body display to preserve line breaks while escaping HTML (security).
- Leave page (US4) is intentionally a scaffold — it will be connected to real data when Phase 4 (Leave Management) is implemented.
- The `check_subscription` middleware already handles employee users via `Auth::user()->client` (verified in `app/Http/Middleware/CheckSubscription.php`).
