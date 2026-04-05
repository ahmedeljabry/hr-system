# Implementation Plan: Super Admin Dashboard

**Branch**: `007-super-admin-dashboard` | **Date**: 2026-04-05 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/007-super-admin-dashboard/spec.md`

## Summary

Upgrade the existing partial super admin area from a static placeholder into a fully functional admin dashboard. This involves: replacing the static dashboard with real live-aggregate stat widgets (via a new `AdminStatsService`), upgrading the client list with pagination + sort + employee count + an inline 3-state status dropdown, adding a client detail page with a read-only employee roster, introducing a user name/email edit interface accessible from both the client list and client detail page, creating a dedicated `layouts/admin.blade.php` sidebar layout, and adding structured audit logging to the application log for all privileged write actions.

No new database migrations are required. All reads and writes target existing `clients`, `employees`, and `users` tables.

## Technical Context

**Language/Version**: PHP 8.3 / Laravel 11
**Primary Dependencies**: Alpine.js, Tailwind CSS, Blade
**Storage**: MySQL 8.0 (production), SQLite (testing)
**Testing**: PHPUnit (Feature tests)
**Target Platform**: Linux Server / Web Browser
**Project Type**: Web Application (Multi-tenant SaaS)
**Performance Goals**: SC-002 — Admin dashboard loads in under 2 seconds for up to 500 clients / 5,000 employees
**Constraints**: CSRF on all forms, `role:super_admin` middleware on all routes, no raw SQL, audit log after successful DB write only
**Scale/Scope**: 5 new/modified screens + 2 new services + 1 new controller + 1 new layout + 5 test classes

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Direct Evidence / Implementation Strategy |
|-----------|--------|------------------------------------------|
| **I. Strict Multi-Tenant Isolation** | ✅ Pass | Super admin reads across ALL tenants by design (this is the privileged cross-tenant role). Client detail page scopes employee query to `client_id`. No risk of accidental cross-tenant leakage because the admin is not a tenant — it has no `client_id` binding. |
| **II. TDD-First** | ✅ Pass | Five feature test classes created before implementation: `DashboardStatsTest`, `ClientListTest`, `ClientDetailTest`, `UserEditTest`, `RouteAccessTest`. Tests written and confirmed RED before implementation begins. |
| **III. Thin Controllers, Fat Services** | ✅ Pass | New `AdminStatsService` handles all aggregate queries. `SubscriptionService::toggleStatus()` extended with audit logging. New `AdminUserService` handles name/email update + validation + logging. Controllers only dispatch and return views. |
| **IV. Bilingual UI First** | ✅ Pass | All new/modified views use `__('key')` localisation helpers. New English and Arabic keys added to `lang/en.json` and `lang/ar.json`. Admin layout sidebar uses `__()` for all labels. |
| **V. Eloquent Database Interactions** | ✅ Pass | All queries use Eloquent. Dashboard counts via `->count()`. Client list via `->withCount('employees')->paginate(15)`. No raw SQL. |

## Project Structure

### Documentation (this feature)

```text
specs/007-super-admin-dashboard/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
app/
├── Services/
│   ├── AdminStatsService.php               # NEW — aggregate stats for dashboard widgets
│   └── AdminUserService.php                # NEW — name/email update + audit logging
├── Http/Controllers/Admin/
│   ├── ClientController.php                # MODIFIED — index() + show() + updateStatus() upgraded
│   └── UserController.php                  # NEW — edit() + update()
resources/views/
├── layouts/
│   └── admin.blade.php                     # NEW — sidebar layout for admin screens
├── admin/
│   ├── dashboard.blade.php                 # MODIFIED — real stat widgets
│   ├── clients/
│   │   ├── index.blade.php                 # MODIFIED — pagination + sort + employee count + dropdown
│   │   └── show.blade.php                  # NEW — client detail with employee list
│   └── users/
│       └── edit.blade.php                  # NEW — edit user name + email
routes/
│   └── admin.php                           # MODIFIED — add show + users.edit + users.update routes
lang/
│   ├── en.json                             # MODIFIED — new admin localisation keys
│   └── ar.json                             # MODIFIED — Arabic translations for new keys
tests/Feature/Admin/
│   ├── DashboardStatsTest.php              # NEW
│   ├── ClientListTest.php                  # NEW
│   ├── ClientDetailTest.php                # NEW
│   ├── UserEditTest.php                    # NEW
│   └── RouteAccessTest.php                 # NEW
```

**Structure Decision**: Standard Laravel Monolith — extending the existing `Admin/` controller namespace and `admin/` view namespace. A new `layouts/admin.blade.php` introduces the sidebar for all admin pages, replacing the current use of `layouts/app.blade.php` in admin views.

## Complexity Tracking

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|--------------------------------------|
| New `layouts/admin.blade.php` | FR spec + clarifications require a sidebar layout for admin (matching employee portal pattern); existing `layouts/app.blade.php` uses top-nav only | Reusing `layouts/app.blade.php` with a conditional sidebar would pollute the shared layout with admin-only logic and break the consistent client/employee experience |
| New `AdminUserService` (separate from `SubscriptionService`) | User editing is a distinct concern; mixing it into `SubscriptionService` would violate Single Responsibility and complicate testing | Adding `updateUser()` directly to `ClientController` would violate Constitution Principle III |

## Detailed Design

### AdminStatsService

```php
// app/Services/AdminStatsService.php
public function getStats(): array
{
    return [
        'total_clients'   => Client::count(),
        'total_employees' => Employee::count(),
        'active_count'    => Client::where('status', 'active')->count(),
        'suspended_count' => Client::where('status', 'suspended')->count(),
        'expired_count'   => Client::where('status', 'expired')->count(),
    ];
}
```

Five independent COUNT queries — no joins, no memory loading of full collections.

---

### ClientController upgrades

**`index()` — upgrade from `get()` to paginated + sorted:**

```php
public function index(Request $request): View
{
    $sortable = ['name', 'status', 'subscription_end'];
    $sort = in_array($request->get('sort'), $sortable) ? $request->get('sort') : 'name';
    $dir  = $request->get('dir') === 'desc' ? 'desc' : 'asc';

    $clients = Client::withCount('employees')
        ->orderBy($sort, $dir)
        ->paginate(15)
        ->withQueryString();

    return view('admin.clients.index', compact('clients', 'sort', 'dir'));
}
```

**`show()` — new method for client detail:**

```php
public function show(Client $client): View
{
    $employees = $client->employees()->with('user')->get();
    return view('admin.clients.show', compact('client', 'employees'));
}
```

**`updateStatus()` — extend with audit logging:**

```php
public function updateStatus(Request $request, Client $client): RedirectResponse
{
    $data = $request->validate(['status' => 'required|in:active,suspended,expired']);
    $old  = $client->status;
    $this->subscriptionService->toggleStatus($client, $data['status']);
    Log::channel('daily')->info('ADMIN_ACTION', [
        'admin_id'  => Auth::id(),
        'action'    => 'status_change',
        'target'    => 'clients',
        'record_id' => $client->id,
        'old'       => $old,
        'new'       => $data['status'],
    ]);
    return back()->with('success', __('Subscription status updated successfully.'));
}
```

---

### AdminUserService

```php
// app/Services/AdminUserService.php
public function updateBasicInfo(User $user, array $data): void
{
    $old = ['name' => $user->name, 'email' => $user->email];
    $user->update(['name' => $data['name'], 'email' => $data['email']]);
    Log::channel('daily')->info('ADMIN_ACTION', [
        'admin_id'  => Auth::id(),
        'action'    => 'user_edit',
        'target'    => 'users',
        'record_id' => $user->id,
        'old'       => $old,
        'new'       => ['name' => $data['name'], 'email' => $data['email']],
    ]);
}
```

---

### UserController (Admin)

```php
// app/Http/Controllers/Admin/UserController.php
public function edit(User $user): View
{
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user): RedirectResponse
{
    $validated = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    ]);
    $this->adminUserService->updateBasicInfo($user, $validated);
    return back()->with('success', __('User updated successfully.'));
}
```

---

### Routes additions (`routes/admin.php`)

```php
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
```

---

### Admin Layout sidebar (`layouts/admin.blade.php`)

Modelled on `layouts/employee.blade.php`. Key differences:
- Sidebar links: **Dashboard** (`/admin/dashboard`) and **Clients** (`/admin/clients`) only.
- Active link detection: `request()->is('admin/dashboard*')` and `request()->is('admin/clients*')`.
- RTL support: same `dir="rtl"` / `mr-64` vs `ml-64` conditional as `layouts/employee.blade.php`.
- Sidebar classes: `w-64 bg-white border-r sticky top-0 h-screen overflow-y-auto flex-shrink-0`.

---

### Inline status dropdown (clients/index.blade.php)

Replace the current Suspend/Activate button forms with:

```html
<form action="{{ route('admin.clients.status', $client->id) }}" method="POST">
    @csrf @method('PATCH')
    <select name="status" onchange="this.form.submit()"
        class="text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-400">
        <option value="active"    {{ $client->status === 'active'    ? 'selected' : '' }}>{{ __('Active') }}</option>
        <option value="suspended" {{ $client->status === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
        <option value="expired"   {{ $client->status === 'expired'   ? 'selected' : '' }}>{{ __('Expired') }}</option>
    </select>
</form>
```

---

### Sortable column headers (clients/index.blade.php)

```html
<a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => ($sort === 'name' && $dir === 'asc') ? 'desc' : 'asc']) }}">
    {{ __('Company Name') }}
    @if($sort === 'name') <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span> @endif
</a>
```

Same pattern for `status` and `subscription_end` columns.

---

## Localisation Keys

New keys to add to `lang/en.json` and `lang/ar.json`:

| English Key | English Value | Arabic Value |
|---|---|---|
| `Total Clients` | Total Clients | إجمالي العملاء |
| `Total Employees` | Total Employees | إجمالي الموظفين |
| `Active Subscriptions` | Active Subscriptions | اشتراكات نشطة |
| `Suspended Subscriptions` | Suspended Subscriptions | اشتراكات معلقة |
| `Expired Subscriptions` | Expired Subscriptions | اشتراكات منتهية |
| `Company Name` | Company Name | اسم الشركة |
| `Subscription Status` | Subscription Status | حالة الاشتراك |
| `Subscription End` | Subscription End | تاريخ انتهاء الاشتراك |
| `Employees` | Employees | الموظفين |
| `Active` | Active | نشط |
| `Suspended` | Suspended | معلق |
| `Expired` | Expired | منتهي |
| `Has Login` | Has Login | لديه حساب |
| `No Login` | No Login | لا يوجد حساب |
| `No clients found.` | No clients found. | لا يوجد عملاء. |
| `No employees found.` | No employees found. | لا يوجد موظفون. |
| `Edit User` | Edit User | تعديل المستخدم |
| `User updated successfully.` | User updated successfully. | تم تحديث المستخدم بنجاح. |
| `Subscription status updated successfully.` | Subscription status updated successfully. | تم تحديث حالة الاشتراك بنجاح. |
| `View Employees` | View Employees | عرض الموظفين |
| `Back to Clients` | Back to Clients | العودة إلى العملاء |
| `Clients` | Clients | العملاء |
| `Dashboard` | Dashboard | لوحة التحكم |
