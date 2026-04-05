# Implementation Plan: Employee Portal

**Branch**: `006-employee-portal` | **Date**: 2026-04-05 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/006-employee-portal/spec.md`

## Summary

Build a comprehensive, data-driven employee self-service portal that replaces the current placeholder dashboard with real aggregate widgets, adds a profile viewer with secure document access, introduces a company announcements system (Client CRUD + Employee read-only feed), scaffolds a leave balance/history page, and provides a flat sidebar navigation covering all 7 portal sections. The portal enforces subscription-gated access and strict multi-tenant isolation.

## Technical Context

**Language/Version**: PHP 8.3 / Laravel 11  
**Primary Dependencies**: Alpine.js, Tailwind CSS, Blade  
**Storage**: MySQL 8.0 (production), SQLite (testing)  
**Testing**: PHPUnit (Feature tests)  
**Target Platform**: Linux Server / Web Browser  
**Project Type**: Web Application (Multi-tenant SaaS)  
**Performance Goals**: SC-002 (Dashboard load < 3s for 100 records per module)  
**Constraints**: CSRF protection, multi-tenant isolation, no raw SQL, subscription enforcement  
**Scale/Scope**: 7 portal screens + 1 new DB entity (Announcement) + sidebar layout

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Direct Evidence / Implementation Strategy |
|-----------|--------|------------------------------------------|
| **I. Strict Multi-Tenant Isolation** | ✅ Pass | All queries scoped via authenticated employee's `client_id`. Announcements use `BelongsToTenant` trait. Dashboard widgets filtered by employee's own records. |
| **II. TDD-First** | ✅ Pass | Feature tests for dashboard widgets, profile access, announcement CRUD, and tenant isolation written before implementation. |
| **III. Thin Controllers, Fat Services** | ✅ Pass | `DashboardService` aggregates widget data. `AnnouncementService` handles CRUD logic. `ProfileService` handles document access. Controllers only dispatch and return views. |
| **IV. Bilingual UI First** | ✅ Pass | All new views use `__('key')` localisation helpers. Sidebar labels, widget titles, and empty states fully translated in `en.json` and `ar.json`. |
| **V. Eloquent Database Interactions** | ✅ Pass | All queries use Eloquent. Dashboard counts via `->count()`, `->latest()`, `->sum()`. No raw SQL. |

## Project Structure

### Documentation (this feature)

```text
specs/006-employee-portal/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
└── tasks.md             # Phase 2 output (/speckit-tasks)
```

### Source Code (repository root)

```text
app/
├── Models/
│   └── Announcement.php                          # NEW — with BelongsToTenant
├── Services/
│   ├── DashboardService.php                      # NEW — aggregate widget data
│   └── AnnouncementService.php                   # NEW — CRUD logic
├── Http/Controllers/
│   ├── Client/
│   │   └── AnnouncementController.php            # NEW — Client CRUD
│   └── Employee/
│       ├── DashboardController.php               # NEW — replaces closure
│       ├── ProfileController.php                 # NEW — profile + docs
│       ├── AnnouncementController.php            # NEW — read-only feed
│       └── LeaveController.php                   # NEW — scaffold for Phase 4
database/
├── migrations/
│   └── xxxx_create_announcements_table.php       # NEW
├── factories/
│   └── AnnouncementFactory.php                   # NEW
resources/views/
├── layouts/
│   └── employee.blade.php                        # NEW — sidebar layout for employees
├── client/
│   └── announcements/
│       ├── index.blade.php                       # NEW
│       ├── create.blade.php                      # NEW
│       └── edit.blade.php                        # NEW
└── employee/
    ├── dashboard.blade.php                       # MODIFIED — real widgets
    ├── profile/
    │   └── index.blade.php                       # NEW
    ├── announcements/
    │   └── index.blade.php                       # NEW
    └── leaves/
        └── index.blade.php                       # NEW — scaffold
tests/Feature/
├── Client/
│   └── AnnouncementTest.php                      # NEW
└── Employee/
    ├── DashboardTest.php                         # NEW
    ├── ProfileTest.php                           # NEW
    └── AnnouncementVisibilityTest.php            # NEW
```

**Structure Decision**: Standard Laravel Monolith — extending existing `Client/` and `Employee/` controller namespaces. A new `layouts/employee.blade.php` introduces the flat sidebar for employee-only pages while preserving the existing `layouts/app.blade.php` for shared elements.

## Complexity Tracking

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|--------------------------------------|
| New layout file (`employee.blade.php`) | FR-012 requires a persistent flat sidebar for employees only; the existing `app.blade.php` uses a top nav without sidebar | Reusing `app.blade.php` with conditional sidebar would add excessive complexity and break the client/admin experience |
