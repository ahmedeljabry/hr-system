# Research: Super Admin Dashboard

**Branch**: `007-super-admin-dashboard` | **Date**: 2026-04-05

## Codebase Audit Findings

### What Already Exists

A significant portion of Phase 7 infrastructure is already partially built:

| Asset | Path | State |
|---|---|---|
| Admin route group | `routes/admin.php` | Exists — `auth + role:super_admin` middleware, `/admin/dashboard`, `/admin/clients` index + status PATCH + subscription PATCH |
| `ClientController` (Admin) | `app/Http/Controllers/Admin/ClientController.php` | Exists — `index()`, `updateStatus()`, `updateSubscription()` |
| `SubscriptionService` | `app/Services/SubscriptionService.php` | Exists — `toggleStatus()`, `setEndDate()`, `isActive()` |
| Admin dashboard view | `resources/views/admin/dashboard.blade.php` | Exists — static placeholder, no real data widgets |
| Admin clients view | `resources/views/admin/clients/index.blade.php` | Exists — lists clients, toggle active/suspended only (not 3-state inline dropdown), no pagination, no sort, no employee count column |
| Admin layout | `resources/views/layouts/` | `app.blade.php` + `employee.blade.php` exist; **no `admin.blade.php`** |
| `Client` model | `app/Models/Client.php` | Exists — `isActive()`, `isSuspended()`, `isExpired()`, `employees()` hasMany, `isNearExpiry()` |
| `Employee` model | `app/Models/Employee.php` | Exists — `client_id`, `user_id` (nullable), `name`, `position`, `hire_date`, softDeletes |
| `User` model | `app/Models/User.php` | Exists — `name`, `email`, `role`, `client_id` fillable |

### Gaps vs Spec Requirements

| Requirement | Gap |
|---|---|
| FR-001: Stats widgets on dashboard | Dashboard is a static placeholder — no `AdminStatsService`, no real counts rendered |
| FR-002: Pagination (15/page) + sort | `ClientController::index()` uses `Client::latest()->get()` — no pagination, no sort |
| FR-002: Employee count column | Clients list has no employee count column |
| FR-003: Inline dropdown (3-state) | Current UI has separate "Suspend/Activate" buttons only — no dropdown, expired state inaccessible from list |
| FR-004: Client detail + employee list | No `show()` route or view for client detail — no employee list sub-page |
| FR-005: Edit user name/email | No admin user edit controller, route, or view exists |
| FR-006: Role guard | Already implemented via `role:super_admin` middleware ✅ |
| FR-007: Bilingual UI | Existing views use hardcoded Arabic text — need `__()` keys for all new/modified admin views |
| FR-008: Empty states | Existing clients list has no empty state for zero clients |
| FR-009: Audit logging | No logging in `SubscriptionService::toggleStatus()` or any admin action |
| `layouts/admin.blade.php` | Does not exist — dashboard currently extends `layouts.app` |

---

## Decision Log

### Decision 1: AdminStatsService — new service vs extending SubscriptionService

- **Decision**: Create a new `AdminStatsService` class.
- **Rationale**: `SubscriptionService` handles mutation of subscription state. Stats aggregation is read-only and cross-entity (clients + employees + users). Mixing them violates Constitution Principle III (Single Responsibility). A dedicated service is also independently testable.
- **Alternatives considered**: Adding `getStats()` to `SubscriptionService` — rejected because it would couple subscription business logic with unrelated read aggregation.

### Decision 2: Sorting mechanism — Eloquent orderBy vs server-side JS

- **Decision**: Server-side sort via query string (`?sort=name&dir=asc`) handled in `ClientController::index()`.
- **Rationale**: The client list is paginated server-side; client-side JS sort would only sort the current page, not the full dataset. Query-string sort integrates naturally with Laravel's paginator (sort params persist across page links).
- **Alternatives considered**: Alpine.js client-side sort — rejected for above reason. A dedicated sort package — rejected as unnecessary overhead.

### Decision 3: Inline status dropdown — plain HTML `<select>` + Alpine.js auto-submit vs AJAX

- **Decision**: Plain `<select>` inside a `<form>` with Alpine.js `@change="$el.closest('form').submit()"` — standard form POST to existing `PATCH /admin/clients/{client}/status`.
- **Rationale**: No JS fetch/XHR complexity. Reuses the already-working `updateStatus()` controller action. Flash messages already work via `back()->with('success', ...)`. CSRF automatically handled by the form.
- **Alternatives considered**: Fetch API AJAX call — rejected because it requires a JSON response path and JS error handling, adding complexity without UX benefit for an admin-only interface.

### Decision 4: Audit logging — Laravel `Log::info()` vs custom channel

- **Decision**: Use `Log::channel('daily')->info()` with a structured message.
- **Rationale**: `config/logging.php` already configures the `daily` channel. No new infrastructure. Log entries are human-readable and greppable. Satisfies FR-009 without a new migration.
- **Alternatives considered**: Custom `admin` log channel — useful but out of scope; the `daily` channel is sufficient. DB audit table — explicitly rejected in the spec (no new migrations).

### Decision 5: Admin layout — extend `layouts.employee` vs write from scratch

- **Decision**: Write `layouts/admin.blade.php` modelled on `layouts/employee.blade.php` but adapted for admin navigation (2 links: Dashboard + Clients).
- **Rationale**: `layouts/employee.blade.php` already implements the sticky sidebar, RTL toggle, language switcher, and Alpine.js hamburger — copy-adapt is faster and consistent. The admin sidebar has only 2 links vs employee's 7, so it is simpler.
- **Alternatives considered**: Reusing `layouts/app.blade.php` (top-nav) — rejected because the spec requires a sidebar layout for admin, consistent with the employee portal.

### Decision 6: User edit form — separate `AdminUserController` vs adding to `ClientController`

- **Decision**: Create `app/Http/Controllers/Admin/UserController.php` with `edit()` and `update()` methods.
- **Rationale**: User editing is a distinct concern from client subscription management. A separate controller keeps `ClientController` focused and follows Constitution Principle III. The `UserController` is also independently testable.
- **Alternatives considered**: Adding `editUser()` / `updateUser()` to `ClientController` — rejected (violates single responsibility).

### Decision 7: `Client::name` vs `Client::company_name`

- **Decision**: The existing `Client` model uses `name` (not `company_name`) based on `$fillable` inspection. The spec uses "company name" as a display label — the underlying field is `clients.name`. All plan references will use `$client->name`.
- **Rationale**: Reflects actual codebase — no rename needed.
- **Alternatives considered**: None — this is a factual discovery, not a decision.
