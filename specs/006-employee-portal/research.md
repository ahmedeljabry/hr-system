# Research: Employee Portal

**Feature**: 006-employee-portal  
**Date**: 2026-04-05

## Research Areas

### 1. Employee Sidebar Layout Strategy

**Decision**: Create a dedicated `layouts/employee.blade.php` that extends or replaces `app.blade.php` for employee routes.

**Rationale**: The current `app.blade.php` uses a top-bar navigation with role-conditional links. FR-012 requires a flat sidebar with 7 persistent links for employees. Embedding a conditional sidebar inside the shared layout adds excessive complexity and risks breaking client/admin views.

**Alternatives Considered**:
- **Conditional sidebar in `app.blade.php`**: Rejected — pollutes shared layout with role logic; hard to maintain.
- **Alpine.js toggle sidebar**: Rejected — sidebar must be persistent, not collapsible per FR-012.

**Implementation**: New `resources/views/layouts/employee.blade.php` that includes a left sidebar (7 links) and main content area. Employee views use `@extends('layouts.employee')` instead of `@extends('layouts.app')`.

---

### 2. Dashboard Widget Data Aggregation

**Decision**: Use a `DashboardService` class that accepts the authenticated employee and returns a data object with all widget values.

**Rationale**: Constitution Principle III requires fat services. Aggregating counts from 4+ tables (tasks, assets, payslips, leave) in a controller would violate this. A service keeps the controller thin and makes the aggregation testable in isolation.

**Data Sources**:
- **Tasks Widget**: `Task::where('employee_id', $employee->id)->where('status', '!=', 'done')->count()`
- **Assets Widget**: `Asset::where('employee_id', $employee->id)->count()`
- **Payslip Widget**: `Payslip::where('employee_id', $employee->id)->latest()->first()` → net_salary
- **Leave Widget**: Deferred to empty state until Phase 4 tables exist
- **Announcements Widget**: `Announcement::where('client_id', $employee->client_id)->latest()->take(3)->get()`

**Performance**: Each query is simple indexed lookup. For 100 records per table, all execute in < 50ms combined.

---

### 3. Secure Document Serving for Employee Profile

**Decision**: Reuse the existing `EmployeeFileController` pattern from `routes/client.php` but add an employee-facing route that restricts access to the authenticated employee's own documents only.

**Rationale**: The client-side file controller already handles tenant-scoped, private-disk file serving. The employee equivalent simply adds an ownership check (`$employee->id === Auth::user()->employee->id`).

**Route**: `GET /employee/profile/documents/{type}` where `type` is `national_id` or `contract`.

**Security**: Files served via `response()->file()` from `storage/app/private/employees/` — never exposed as public URLs.

---

### 4. Subscription Enforcement on Employee Routes

**Decision**: Add the existing `check_subscription` middleware to the employee route group in `routes/employee.php`.

**Rationale**: Currently employee routes only have `['auth', 'role:employee']`. The clarification (Q1) confirmed that employees of expired-subscription clients must be fully blocked. The `CheckSubscription` middleware already exists and handles client lookup + redirect.

**Implementation**: Change middleware from `['auth', 'role:employee']` to `['auth', 'role:employee', 'check_subscription']`.

**Note**: The `CheckSubscription` middleware must resolve the client from `Auth::user()->client_id` — employees have this field via the `users` table.

---

### 5. Announcement Entity Design

**Decision**: Simple `announcements` table with `client_id`, `title`, `body` (TEXT), `published_at` (TIMESTAMP).

**Rationale**: Spec requires plain text with preserved line breaks (Q3 clarification). No rich text editor, no attachments. `published_at` supports future "schedule for later" functionality but for now is set to `now()` on creation.

**Soft Delete**: Not required per spec — hard delete is fine since clients manage their own content.

**Pagination**: 10 per page (FR-006), using Laravel's built-in `->paginate(10)`.
