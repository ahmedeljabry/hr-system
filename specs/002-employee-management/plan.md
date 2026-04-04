# Implementation Plan: Client Dashboard & Employee Management

**Branch**: `002-employee-management` | **Date**: 2026-04-04 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/002-employee-management/spec.md`

## Summary

This phase implements the Client Dashboard and Employee Management module. It provides full CRUD operations for employee records, a robust bulk import tool via `.xlsx` (using Maatwebsite/Laravel-Excel), and comprehensive dashboard analytics with proactive subscription renewal warnings. All database interactions enforce strict multi-tenant isolation via the `client_id` foreign key.

## Technical Context

**Language/Version**: PHP 8.3 (Laravel 11)
**Primary Dependencies**: Maatwebsite/Laravel-Excel, Alpine.js, Blade templates
**Storage**: MySQL 8 (production) / SQLite (testing), Local `private` storage disk for file uploads
**Testing**: PHPUnit (Feature tests, TDD-first)
**Target Platform**: Web (responsive, bilingual AR/EN)
**Project Type**: Multi-tenant Web Application
**Performance Goals**: Parse and insert 500 employee rows from `.xlsx` in under 10 seconds
**Constraints**: Absolute data isolation per `client_id`. Private files inaccessible via public URLs.
**Scale/Scope**: ~1,000 employees per client. 3 new views + 1 dashboard enhancement.

## Constitution Check

*GATE: Passed — Pre-design and Post-design*

| Principle | Status | Evidence |
|-----------|--------|----------|
| Multi-tenant isolation | ✅ PASS | All queries scoped via `client_id` in `EmployeeService` |
| Role-guard every route | ✅ PASS | Routes wrapped in `auth` + `role:client` middleware |
| Business logic in Services | ✅ PASS | `EmployeeService` handles CRUD + import logic; controllers are thin |
| TDD: tests before code | ✅ PASS | Feature tests planned: `EmployeeTest`, `EmployeeImportTest`, `DashboardTest` |
| No raw SQL | ✅ PASS | Eloquent ORM only |
| CSRF protection | ✅ PASS | All forms use `@csrf` |
| File uploads: private disk | ✅ PASS | `Storage::disk('private')` with middleware-gated access |
| PSR-12 code style | ✅ PASS | Standard Laravel formatting |

## Project Structure

### Documentation (this feature)

```text
specs/002-employee-management/
├── plan.md              # This file
├── research.md          # Phase 0 output — technology decisions
├── data-model.md        # Phase 1 output — Employee entity schema
├── quickstart.md        # Phase 1 output — developer onboarding
├── contracts/
│   └── routes.md        # Phase 1 output — HTTP endpoint contracts
└── tasks.md             # Phase 2 output (/speckit-tasks command)
```

### Source Code (repository root)

```text
app/
├── Models/
│   └── Employee.php                          # New model
├── Services/
│   └── EmployeeService.php                   # New service (CRUD + tenant scoping)
├── Imports/
│   └── EmployeesImport.php                   # New (Maatwebsite import class)
├── Http/
│   ├── Controllers/Client/
│   │   ├── EmployeeController.php            # New controller
│   │   └── DashboardController.php           # Enhanced with metrics
│   └── Requests/
│       └── StoreEmployeeRequest.php          # New form request

database/
└── migrations/
    └── xxxx_create_employees_table.php       # New migration

resources/views/client/
├── dashboard.blade.php                       # Enhanced with metrics + expiry banner
└── employees/
    ├── index.blade.php                       # Paginated employee list
    ├── create.blade.php                      # Add employee form
    ├── edit.blade.php                        # Edit employee form
    ├── show.blade.php                        # Employee detail view
    └── import.blade.php                      # Excel upload form

routes/
└── client.php                               # New/enhanced route file

lang/
├── ar/messages.php                          # Extended with employee strings
└── en/messages.php                          # Extended with employee strings

tests/Feature/Client/
├── EmployeeTest.php                         # CRUD + tenant isolation tests
├── EmployeeImportTest.php                   # Excel import tests
└── DashboardTest.php                        # Dashboard metrics + banner tests
```

**Structure Decision**: Standard Laravel 11 monolith. All client-facing code lives under `App\Http\Controllers\Client` and `resources/views/client/`, cleanly separated from admin and employee route groups established in Phase 1.

## Complexity Tracking

> No constitution violations. No complexity justifications needed.
