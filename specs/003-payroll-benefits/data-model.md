# Data Model: Payroll & Benefits (Phase 3)

**Feature**: `003-payroll-benefits` | **Date**: 2026-04-04

## Entity Relationship Diagram

```
Client (existing)
  ├── has many: Employee (existing, from Phase 2)
  │     ├── has many: SalaryComponent
  │     ├── has many: Payslip (via payroll runs)
  │     └── belongs to: User (optional → required after account creation)
  └── has many: PayrollRun
        └── has many: Payslip
              └── has many: PayslipLineItem
```

## Tables

### `salary_components` (NEW)

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | bigint unsigned | PK, auto-increment | |
| employee_id | bigint unsigned | FK → employees.id, cascade delete | |
| type | enum('allowance','deduction') | NOT NULL | |
| name | varchar(255) | NOT NULL | e.g., "Housing", "Insurance" |
| amount | decimal(10,2) | NOT NULL, min: 0 | Monthly amount |
| created_at | timestamp | | |
| updated_at | timestamp | | |

**Indexes**: `(employee_id)`, `(employee_id, type)`

**Validation Rules**:
- `name`: required, string, max 255
- `type`: required, in [allowance, deduction]
- `amount`: required, numeric, min 0
- Tenant isolation enforced at Service layer (employee must belong to authenticated client)

---

### `payroll_runs` (NEW)

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | bigint unsigned | PK, auto-increment | |
| client_id | bigint unsigned | FK → clients.id, cascade delete | |
| month | date | NOT NULL | First day of month (e.g., 2026-03-01) |
| status | enum('draft','confirmed') | NOT NULL, default: 'draft' | |
| confirmed_at | timestamp | NULLABLE | Set when status changes to confirmed |
| created_at | timestamp | | |
| updated_at | timestamp | | |

**Indexes**: `(client_id)`, UNIQUE `(client_id, month)` WHERE status = 'confirmed' (application-level enforcement)

**State Transitions**:
- `draft` → `confirmed` (one-way, irreversible)
- No deletion of confirmed runs

**Validation Rules**:
- `month`: required, date, must not be a future month
- Duplicate confirmed run prevention per (client_id, month)

---

### `payslips` (NEW)

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | bigint unsigned | PK, auto-increment | |
| payroll_run_id | bigint unsigned | FK → payroll_runs.id, cascade delete | |
| employee_id | bigint unsigned | FK → employees.id, cascade delete | |
| basic_salary | decimal(10,2) | NOT NULL | Snapshot of employee.basic_salary |
| total_allowances | decimal(10,2) | NOT NULL, default: 0 | SUM of allowance line items |
| total_deductions | decimal(10,2) | NOT NULL, default: 0 | SUM of deduction line items |
| net_salary | decimal(10,2) | NOT NULL | basic + allowances - deductions |
| created_at | timestamp | | |
| updated_at | timestamp | | |

**Indexes**: `(payroll_run_id)`, `(employee_id)`, UNIQUE `(payroll_run_id, employee_id)`

**Invariant**: `net_salary = basic_salary + total_allowances - total_deductions`

---

### `payslip_line_items` (NEW)

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | bigint unsigned | PK, auto-increment | |
| payslip_id | bigint unsigned | FK → payslips.id, cascade delete | |
| component_name | varchar(255) | NOT NULL | Frozen snapshot of salary_component.name |
| type | enum('allowance','deduction') | NOT NULL | Frozen snapshot of salary_component.type |
| amount | decimal(10,2) | NOT NULL | Frozen snapshot of salary_component.amount |
| created_at | timestamp | | |
| updated_at | timestamp | | |

**Indexes**: `(payslip_id)`, `(payslip_id, type)`

---

### `employees` (EXISTING — Phase 2, modified)

| Column | Change | Notes |
|--------|--------|-------|
| user_id | Already nullable FK → users.id | Set when client creates employee account |

No schema change needed. The `user_id` column already exists and is nullable from Phase 2.

### `users` (EXISTING — Phase 1)

No schema change needed. New rows with `role=employee` will be created by the employee account creation feature.

## Relationships Summary

| Model | Relationship | Target |
|-------|-------------|--------|
| Employee | hasMany | SalaryComponent |
| Employee | hasMany | Payslip |
| Employee | belongsTo | User (nullable until account created) |
| Client | hasMany | PayrollRun |
| PayrollRun | hasMany | Payslip |
| PayrollRun | belongsTo | Client |
| Payslip | belongsTo | PayrollRun |
| Payslip | belongsTo | Employee |
| Payslip | hasMany | PayslipLineItem |
| PayslipLineItem | belongsTo | Payslip |
| SalaryComponent | belongsTo | Employee |
