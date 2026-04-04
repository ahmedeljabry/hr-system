# Implementation Plan: Payroll & Benefits

**Branch**: `003-payroll-benefits` | **Date**: 2026-04-04 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/003-payroll-benefits/spec.md`

## Summary

This phase implements the Payroll & Benefits module. It adds salary component management (allowances/deductions per employee), a monthly payroll run engine with draft→confirm workflow, payslip generation with itemized line-item snapshots, client-side payroll history & review, and employee self-service payslip viewing. It also introduces employee user account creation so employees can log in and access their payslips. All payroll data is strictly tenant-isolated via `client_id` scoping through dedicated Service classes.

## Technical Context

**Language/Version**: PHP 8.3 (Laravel 11)
**Primary Dependencies**: Alpine.js, Blade templates (no new packages required)
**Storage**: MySQL 8 (production) / SQLite (testing)
**Testing**: PHPUnit (Feature tests, TDD-first)
**Target Platform**: Web (responsive, bilingual AR/EN)
**Project Type**: Multi-tenant Web Application
**Performance Goals**: Payroll run for 200 employees completes within 15 seconds
**Constraints**: Absolute data isolation per `client_id`. Payslip snapshots are immutable after confirmation.
**Scale/Scope**: ~1,000 employees per client. 4 new migrations, 2 new services, 8+ new views, 1 new employee route group.

## Constitution Check

*GATE: Passed — Pre-design and Post-design*

| Principle | Status | Evidence |
|-----------|--------|----------|
| Multi-tenant isolation | ✅ PASS | All queries scoped via `client_id` in `PayrollService` and `SalaryComponentService` |
| Role-guard every route | ✅ PASS | Client routes: `auth` + `role:client` + `check_subscription`. Employee routes: `auth` + `role:employee` |
| Business logic in Services | ✅ PASS | `PayrollService` handles run/confirm logic; `SalaryComponentService` handles CRUD; controllers are thin |
| TDD: tests before code | ✅ PASS | Feature tests planned: `SalaryComponentTest`, `PayrollRunTest`, `PayslipTest`, `EmployeePayslipTest` |
| No raw SQL | ✅ PASS | Eloquent ORM only (aggregate queries use Eloquent `sum()`) |
| CSRF protection | ✅ PASS | All forms use `@csrf` |
| File uploads: private disk | N/A | No file uploads in this phase |
| PSR-12 code style | ✅ PASS | Standard Laravel formatting |

## Project Structure

### Documentation (this feature)

```text
specs/003-payroll-benefits/
├── plan.md              # This file
├── research.md          # Phase 0 output — technology decisions
├── data-model.md        # Phase 1 output — Entity schemas
├── quickstart.md        # Phase 1 output — developer onboarding
├── contracts/
│   └── routes.md        # Phase 1 output — HTTP endpoint contracts
└── tasks.md             # Phase 2 output (/speckit-tasks command)
```

### Source Code (repository root)

```text
app/
├── Models/
│   ├── SalaryComponent.php                   # New model
│   ├── PayrollRun.php                        # New model
│   ├── Payslip.php                           # New model
│   └── PayslipLineItem.php                   # New model
├── Services/
│   ├── SalaryComponentService.php            # New service (CRUD + tenant scoping)
│   └── PayrollService.php                    # New service (run, confirm, history)
├── Http/
│   ├── Controllers/Client/
│   │   ├── SalaryComponentController.php     # New controller
│   │   ├── PayrollController.php             # New controller
│   │   └── EmployeeAccountController.php     # New controller (employee user creation)
│   ├── Controllers/Employee/
│   │   └── PayslipController.php             # New controller (employee self-service)
│   └── Requests/
│       ├── StoreSalaryComponentRequest.php    # New form request
│       └── RunPayrollRequest.php             # New form request

database/
├── factories/
│   ├── SalaryComponentFactory.php            # New factory
│   ├── PayrollRunFactory.php                 # New factory
│   └── PayslipFactory.php                    # New factory
└── migrations/
    ├── xxxx_create_salary_components_table.php
    ├── xxxx_create_payroll_runs_table.php
    ├── xxxx_create_payslips_table.php
    └── xxxx_create_payslip_line_items_table.php

resources/views/client/
├── employees/
│   └── salary-components.blade.php           # Salary component CRUD inline view
├── payroll/
│   ├── index.blade.php                       # Payroll history list
│   ├── run.blade.php                         # Select month + run payroll
│   ├── show.blade.php                        # Payroll run detail (all payslips)
│   └── confirm.blade.php                     # Review draft + confirm button
└── employees/
    └── create-account.blade.php              # Generate employee login form

resources/views/employee/
├── dashboard.blade.php                       # Basic employee dashboard
└── payslips/
    ├── index.blade.php                       # Payslip month list
    └── show.blade.php                        # Payslip detail (itemized)

routes/
├── client.php                               # Extended with payroll + salary component routes
└── employee.php                             # New route file for employee self-service

lang/
├── ar/messages.php                          # Extended with payroll/payslip strings
└── en/messages.php                          # Extended with payroll/payslip strings

tests/Feature/
├── Client/
│   ├── SalaryComponentTest.php              # CRUD + tenant isolation tests
│   ├── PayrollRunTest.php                   # Run + confirm + duplicate prevention tests
│   └── EmployeeAccountTest.php              # Employee user creation tests
└── Employee/
    └── PayslipTest.php                      # Self-service viewing + isolation tests
```

**Structure Decision**: Continues the standard Laravel 11 monolith pattern from Phase 2. Client payroll logic lives under `App\Http\Controllers\Client`. A new `App\Http\Controllers\Employee` namespace is introduced for the employee-facing payslip portal, with a dedicated `routes/employee.php` route file guarded by `auth` + `role:employee` middleware.

## Complexity Tracking

> No constitution violations. No complexity justifications needed.
