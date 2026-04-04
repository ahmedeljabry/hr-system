# Quickstart: Payroll & Benefits (Phase 3)

**Feature**: `003-payroll-benefits` | **Date**: 2026-04-04

## Prerequisites

- Phase 1 (Foundation & Auth) must be complete: users, clients, roles, middleware
- Phase 2 (Employee Management) must be complete: employees table, Employee model, EmployeeService
- PHP 8.3, Composer, Laravel 11 installed
- Database migrated through Phase 2

## Getting Started

```bash
# 1. Ensure you're on the correct branch
git checkout 003-payroll-benefits

# 2. Run Phase 3 migrations (4 new tables)
php artisan migrate

# 3. Verify new tables exist
php artisan tinker --execute="echo implode(', ', Schema::getTableListing());"
# Should include: salary_components, payroll_runs, payslips, payslip_line_items

# 4. Run tests (TDD — tests should be written first and fail before implementation)
php artisan test --filter=SalaryComponentTest
php artisan test --filter=PayrollRunTest
php artisan test --filter=PayslipTest
php artisan test --filter=EmployeeAccountTest

# 5. Run full test suite (including Phase 1 & 2 regression)
php artisan test
```

## Key Development Flows

### 1. Salary Component Management
- Client navigates to: `/client/employees/{id}/salary-components`
- Adds allowances and deductions per employee
- Service: `SalaryComponentService` (CRUD + tenant scoping)

### 2. Payroll Run
- Client navigates to: `/client/payroll/run`
- Selects a month → system generates draft payslips for all employees
- Reviews draft → confirms
- Service: `PayrollService::runPayroll($clientId, $month)` and `PayrollService::confirmRun($clientId, $runId)`

### 3. Employee Payslip Viewing
- Client creates employee account: `/client/employees/{id}/create-account`
- Employee logs in and navigates to: `/employee/payslips`
- Views itemized payslip detail

## New Route Files

- `routes/client.php` — Extended with salary component and payroll routes
- `routes/employee.php` — **New file** for employee self-service (register in `routes/web.php`)

## Architecture Notes

- **Payslip snapshots are immutable**: Once a payroll run is confirmed, payslips and their line items cannot be edited
- **Tenant isolation**: All payroll data scoped by `client_id` at the Service layer
- **Two roles interact**: Clients manage payroll, employees view payslips (read-only)
- **No new packages required**: Built entirely with Laravel's native Eloquent and Blade
