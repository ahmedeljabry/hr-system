# HTTP Route Contracts: Payroll & Benefits (Phase 3)

**Feature**: `003-payroll-benefits` | **Date**: 2026-04-04

## Client Routes (`routes/client.php`)

Middleware: `['auth', 'role:client', 'check_subscription']` | Prefix: `/client`

### Salary Components

| Method | URI | Controller@Method | Name | Description |
|--------|-----|-------------------|------|-------------|
| GET | `/client/employees/{employee}/salary-components` | `SalaryComponentController@index` | `client.salary-components.index` | List employee's salary components |
| POST | `/client/employees/{employee}/salary-components` | `SalaryComponentController@store` | `client.salary-components.store` | Add a salary component |
| PUT | `/client/employees/{employee}/salary-components/{component}` | `SalaryComponentController@update` | `client.salary-components.update` | Edit a salary component |
| DELETE | `/client/employees/{employee}/salary-components/{component}` | `SalaryComponentController@destroy` | `client.salary-components.destroy` | Delete a salary component |

### Payroll

| Method | URI | Controller@Method | Name | Description |
|--------|-----|-------------------|------|-------------|
| GET | `/client/payroll` | `PayrollController@index` | `client.payroll.index` | Payroll history (all runs) |
| GET | `/client/payroll/run` | `PayrollController@create` | `client.payroll.create` | Select month form |
| POST | `/client/payroll/run` | `PayrollController@store` | `client.payroll.store` | Execute payroll run |
| GET | `/client/payroll/{payrollRun}` | `PayrollController@show` | `client.payroll.show` | View payroll run detail (all payslips) |
| POST | `/client/payroll/{payrollRun}/confirm` | `PayrollController@confirm` | `client.payroll.confirm` | Confirm a draft payroll run |

### Employee Account Creation

| Method | URI | Controller@Method | Name | Description |
|--------|-----|-------------------|------|-------------|
| GET | `/client/employees/{employee}/create-account` | `EmployeeAccountController@create` | `client.employees.create-account` | Show account creation form |
| POST | `/client/employees/{employee}/create-account` | `EmployeeAccountController@store` | `client.employees.store-account` | Generate employee login credentials |

## Employee Routes (`routes/employee.php` — NEW FILE)

Middleware: `['auth', 'role:employee']` | Prefix: `/employee`

### Dashboard & Payslips

| Method | URI | Controller@Method | Name | Description |
|--------|-----|-------------------|------|-------------|
| GET | `/employee/dashboard` | `Employee\DashboardController@index` | `employee.dashboard` | Basic employee dashboard |
| GET | `/employee/payslips` | `Employee\PayslipController@index` | `employee.payslips.index` | List months with confirmed payslips |
| GET | `/employee/payslips/{payslip}` | `Employee\PayslipController@show` | `employee.payslips.show` | View individual payslip detail (itemized) |

## Route Registration

Add to `routes/web.php` (bottom):
```php
// Employee Routes (Separated)
require __DIR__.'/employee.php';
```

## Access Control Summary

| Role | Salary Components | Run Payroll | View Payroll History | View Payslips | Create Employee Account |
|------|-------------------|-------------|---------------------|---------------|------------------------|
| super_admin | ❌ | ❌ | ❌ | ❌ | ❌ |
| client | ✅ (own employees) | ✅ (own) | ✅ (own) | ✅ (own runs) | ✅ (own employees) |
| employee | ❌ | ❌ | ❌ | ✅ (own only) | ❌ |
