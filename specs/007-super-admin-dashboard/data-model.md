# Data Model: Super Admin Dashboard

**Branch**: `007-super-admin-dashboard` | **Date**: 2026-04-05

## Overview

Phase 7 introduces **no new database tables**. All reads are from existing tables (`users`, `clients`, `employees`). The only writes are:
- `clients.status` — updated via `SubscriptionService::toggleStatus()`
- `users.name`, `users.email` — updated via new `AdminUserService::updateBasicInfo()`

---

## Existing Entities Used (Read + Write)

### `clients` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint unsigned PK | |
| `name` | varchar | Company name — displayed as "Company Name" in UI |
| `status` | enum(`active`,`suspended`,`expired`) | **Written by Phase 7** via inline dropdown |
| `subscription_start` | timestamp nullable | Display only |
| `subscription_end` | timestamp nullable | Display + sortable column |
| `user_id` | FK → `users.id` | Owner account; edit target for FR-005 |
| `created_at` / `updated_at` | timestamps | |

**Relationships used**:
- `Client::employees()` → `hasMany(Employee::class)` — used for employee count (`withCount('employees')`)
- `Client::user()` → `hasOne(User::class)` — used to reach the client owner's User for editing

**Validation rules for status write**:
- `status` must be one of `active`, `suspended`, `expired`

---

### `employees` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint unsigned PK | |
| `client_id` | FK → `clients.id` | Tenant scope — filter by this for detail page |
| `user_id` | FK → `users.id` nullable | `null` = no login account ("No Login" indicator) |
| `name` | varchar | Displayed in employee list |
| `position` | varchar | Displayed in employee list |
| `hire_date` | date | Displayed in employee list |
| `deleted_at` | timestamp nullable | SoftDeletes — default scope excludes soft-deleted; admin sees only active employees |

**Relationships used**:
- `Employee::user()` → `belongsTo(User::class)` — used to determine login account presence (`$employee->user_id !== null`)
- `Employee::client()` → `belongsTo(Client::class)` — used for scoping

---

### `users` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint unsigned PK | |
| `name` | varchar | **Written by Phase 7** via edit form |
| `email` | varchar unique | **Written by Phase 7** via edit form |
| `role` | enum(`super_admin`,`client`,`employee`) | Read-only from admin interface |
| `client_id` | FK → `clients.id` nullable | Used to associate employee users with a tenant |
| `password` | varchar (hashed) | Never exposed or modified in Phase 7 |

**Validation rules for user write**:
- `name`: required, string, max:255
- `email`: required, email, max:255, unique:users,email,{ignore current user id}

---

## Computed Aggregate: AdminStats

Not stored in the database. Computed at request time by `AdminStatsService::getStats()`.

| Key | Source Query | Description |
|---|---|---|
| `total_clients` | `Client::count()` | All registered clients |
| `total_employees` | `Employee::count()` | All employees across all tenants (respects SoftDeletes default scope) |
| `active_count` | `Client::where('status','active')->count()` | Active subscription count |
| `suspended_count` | `Client::where('status','suspended')->count()` | Suspended subscription count |
| `expired_count` | `Client::where('status','expired')->count()` | Expired subscription count |

All five queries run independently. No joins required — each is a single-table COUNT.

---

## State Transitions: `clients.status`

```
active ──────► suspended
   ▲               │
   │               ▼
   └────────── expired
      (any state → any state, manual only)
```

- Transitions are unrestricted — the super admin may set any value from any current state.
- No automatic transitions in Phase 7 (cron job out of scope).
- Each transition is logged to `storage/logs/laravel-{date}.log` (FR-009).
