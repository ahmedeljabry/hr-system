# Research: Payroll & Benefits (Phase 3)

**Feature**: `003-payroll-benefits` | **Date**: 2026-04-04

## Research Tasks

### 1. Payroll Calculation Engine Pattern

**Decision**: Implement payroll calculation as a synchronous Service method (`PayrollService::runPayroll()`) that iterates over all active employees, computes net salary, and bulk-inserts payslips within a database transaction.

**Rationale**: For the target scale (~200 employees per run), synchronous processing is simpler, more debuggable, and well within Laravel's request limits. Queued jobs add complexity (monitoring, failure handling) that isn't justified at this scale.

**Alternatives Considered**:
- **Queued job per employee**: Rejected — adds unnecessary latency and complexity for small batches. Would be appropriate at 10,000+ employees.
- **Stored procedure**: Rejected — violates the constitution mandate of "no raw SQL, use Eloquent only."

### 2. Payslip Snapshot Strategy

**Decision**: Store both aggregated totals on the `payslips` table (basic, total_allowances, total_deductions, net_salary) AND itemized line items in a separate `payslip_line_items` table (component_name, type, amount).

**Rationale**: Aggregates enable fast summary views (payroll history), while line items enable detailed employee payslip view with full audit history. This is standard in payroll systems and was confirmed during clarification.

**Alternatives Considered**:
- **JSON column for line items**: Rejected — harder to query, lacks relational integrity, doesn't leverage MySQL indexing.
- **Reference live salary components**: Rejected — mutations to salary components would retroactively change historical payslips.

### 3. Draft → Confirm State Machine

**Decision**: Simple two-state workflow: `draft` → `confirmed`. No reversal or deletion of confirmed runs.

**Rationale**: Confirmed during clarification. Keeps the implementation simple. A corrective run approach (create a new run) is standard practice in payroll systems and avoids audit trail complications.

**Alternatives Considered**:
- **Multi-state (draft → review → approved → finalized)**: Rejected — over-engineered for single-approver SME clients.
- **Reversible runs**: Rejected — complicates audit trail and snapshot integrity.

### 4. Employee Account Creation

**Decision**: Add an `EmployeeAccountController` in the `Client` namespace that allows clients to generate login credentials (email + auto-generated password) for an employee, creating a `users` row with `role=employee` linked to the `employees.user_id` FK.

**Rationale**: Phase 2 left employees as data-only records. Payslip self-service requires authenticated employee users. The simplest implementation is a "Create Account" button on the employee detail page. The generated password is shown once to the client (who shares it with the employee).

**Alternatives Considered**:
- **Email-based self-registration**: Rejected — requires email service setup, which is out of scope. Would be a Phase 6 enhancement.
- **Defer to Phase 6 (Employee Portal)**: Rejected — clarification confirmed including it in Phase 3 to make US4 functional.

### 5. Duplicate Payroll Run Prevention

**Decision**: Enforce a unique constraint at the database level on `(client_id, month)` for confirmed payroll runs, plus application-level validation before creating a new run.

**Rationale**: Database constraint provides a safety net even if application code has bugs. Application-level check provides user-friendly error messages before hitting the constraint.

**Alternatives Considered**:
- **Application-only check**: Rejected — race conditions could allow duplicates without DB enforcement.
- **Allow multiple runs per month**: Rejected — spec explicitly requires prevention (FR-007).

### 6. Employee Route Group

**Decision**: Create a new `routes/employee.php` file with middleware `['auth', 'role:employee']` and prefix `employee`. Register it in `bootstrap/app.php` alongside existing `routes/client.php` and `routes/admin.php`.

**Rationale**: Clean separation of concerns. Employee routes are distinct from client routes (different role, different views, read-only access). Follows the established pattern from Phases 1-2.

**Alternatives Considered**:
- **Embed employee routes in `routes/web.php`**: Rejected — violates separation of concerns pattern established in Phase 1.
- **Shared route file with conditional middleware**: Rejected — harder to maintain and reason about access control.
