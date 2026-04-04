# Feature Specification: Payroll & Benefits

**Feature Branch**: `003-payroll-benefits`  
**Created**: 2026-04-04  
**Status**: Draft  
**Input**: User description: "Phase 3 — Payroll & Benefits: salary components, monthly payroll runs, payslip generation, and employee self-service payslip viewing"

## Clarifications

### Session 2026-04-04

- Q: Should payroll runs be reversible (e.g., can a confirmed run be re-opened)? → Assumed: No. Once confirmed, a payroll run is final. A new corrective run can be created for the same month if errors are discovered.
- Q: How should the system handle employees added mid-month? → Assumed: The payroll run includes all employees active at the time of execution. Pro-rating is out of scope for this phase; the full monthly salary components are applied.
- Q: Should payslips store an itemized breakdown of each salary component, or only aggregated totals? → A: Store itemized snapshot (each component name, type, amount per payslip) for full audit trail and employee-facing detail.
- Q: Should this phase include creating employee user accounts, or defer employee payslip viewing? → A: Include employee account creation as a prerequisite task in this phase. Clients will be able to generate login credentials for their employees.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Salary Component Management (Priority: P1)

As a client, I want to define salary components (allowances and deductions) for each of my employees so that the payroll calculation accurately reflects their compensation structure.

**Why this priority**: Salary components are the foundational data for any payroll calculation. Without defined allowances and deductions, the system cannot compute net salary. This must exist before any payroll run is possible.

**Independent Test**: Can be fully tested by a client navigating to an employee's profile, adding multiple allowances (e.g., "Housing Allowance", "Transport Allowance") and deductions (e.g., "Insurance", "Tax"), editing their amounts, and deleting one. The employee's total compensation breakdown should update immediately.

**Acceptance Scenarios**:

1. **Given** a logged-in client viewing an employee's details, **When** they click "Manage Salary Components" and add an allowance with name "Housing" and amount 1500, **Then** the component is saved and displayed in the employee's salary breakdown list.
2. **Given** a logged-in client viewing an employee's salary components, **When** they edit the "Housing" allowance amount from 1500 to 2000, **Then** the updated amount is reflected immediately.
3. **Given** a logged-in client viewing an employee's salary components, **When** they delete the "Insurance" deduction, **Then** it is removed from the list and no longer factors into payroll calculations.
4. **Given** a logged-in client, **When** they view the salary components of another tenant's employee, **Then** the system returns a not-found error, preventing cross-tenant data access.

---

### User Story 2 - Monthly Payroll Run (Priority: P1)

As a client, I want to run payroll for a selected month so that the system generates a payslip for every active employee with their net salary calculated from basic salary plus allowances minus deductions.

**Why this priority**: The payroll run is the core deliverable of this phase. It transforms raw salary data into actionable payslip records that employees and clients both rely on.

**Independent Test**: Can be fully tested by a client selecting a month (e.g., "2026-03"), clicking "Run Payroll", and verifying that a payslip record is generated for each active employee showing basic salary, total allowances, total deductions, and net salary. The run starts as "draft" status and can be confirmed.

**Acceptance Scenarios**:

1. **Given** a logged-in client with 5 employees who have salary components configured, **When** they select month "2026-03" and click "Run Payroll", **Then** the system creates a payroll run record (status: draft) and generates 5 individual payslip records with calculated net salaries.
2. **Given** a draft payroll run, **When** the client reviews the generated payslips and clicks "Confirm", **Then** the payroll run status changes to "confirmed" and the run timestamp is recorded.
3. **Given** a confirmed payroll run for month "2026-03", **When** the client attempts to run payroll for the same month again, **Then** the system warns that a confirmed run already exists for that month and prevents duplicate runs.
4. **Given** an employee with basic salary 5000, housing allowance 1500, transport allowance 500, and insurance deduction 300, **When** payroll is run, **Then** the payslip shows: basic=5000, total_allowances=2000, total_deductions=300, net_salary=6700.

---

### User Story 3 - Payroll History & Review (Priority: P2)

As a client, I want to view the history of all payroll runs and drill into any specific run to review individual payslips so that I can audit past compensation and track payroll spending over time.

**Why this priority**: Historical access is important for auditing and compliance but is secondary to the ability to actually run payroll.

**Independent Test**: Can be fully tested by running payroll for multiple months, then navigating to the payroll history page and verifying the list shows each run with month, status, and employee count. Clicking a run should show the breakdown of all payslips.

**Acceptance Scenarios**:

1. **Given** a logged-in client with 3 confirmed payroll runs, **When** they navigate to the payroll history page, **Then** they see a table listing each run with columns: Month, Status, Number of Employees, Total Net Payout, and Run Date.
2. **Given** a logged-in client viewing the payroll history, **When** they click on a specific payroll run, **Then** they see a detailed table of all payslips for that run (employee name, basic, allowances, deductions, net salary).

---

### User Story 4 - Employee Payslip Viewing (Priority: P2)

As an employee, I want to view my own payslips by month so that I can understand my compensation breakdown and keep records of my earnings.

**Why this priority**: Employee self-service reduces operational burden on the client. It is dependent on payroll runs existing first.

**Independent Test**: Can be fully tested by logging in as an employee, navigating to "My Payslips", selecting a month, and verifying the payslip shows basic salary, itemized allowances, itemized deductions, and net salary. The employee must NOT be able to see other employees' payslips.

**Acceptance Scenarios**:

1. **Given** a logged-in employee whose payroll has been run and confirmed for "2026-03", **When** they navigate to "My Payslips", **Then** they see a list of months with confirmed payslips available.
2. **Given** a logged-in employee viewing their payslip for "2026-03", **When** they open the payslip detail, **Then** they see: their name, position, basic salary, each allowance with name and amount, each deduction with name and amount, and the net salary total.
3. **Given** a logged-in employee, **When** they attempt to access another employee's payslip via URL manipulation, **Then** the system returns a not-found or forbidden error.

---

### Edge Cases

- What happens when a client runs payroll for a month where no employees have salary components? → The system should still create the payroll run but flag a warning that no salary data was found for any employees.
- What happens when an employee has zero deductions or zero allowances? → Net salary should equal basic salary plus allowances (if no deductions) or basic salary minus deductions (if no allowances). The calculation must handle zero gracefully.
- What happens if a client deletes a salary component after a payroll run that included it? → The existing payslip records remain unchanged (they capture a point-in-time snapshot). Future runs will use the updated component list.
- What happens when a payroll run is attempted for a future month? → The system should prevent payroll runs for months that have not yet ended, or at minimum for months beyond the current calendar month.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow clients to create, read, update, and delete salary components (type: allowance or deduction) for each of their employees.
- **FR-002**: System MUST enforce that salary components are strictly scoped to the client's own employees (tenant isolation).
- **FR-003**: System MUST allow clients to initiate a monthly payroll run for a selected month, generating a payslip for every active employee.
- **FR-004**: System MUST calculate net salary as: `basic_salary + SUM(allowances) - SUM(deductions)` for each employee during a payroll run.
- **FR-005**: System MUST create payroll runs with an initial "draft" status, allowing the client to review before confirming.
- **FR-006**: System MUST allow clients to confirm a draft payroll run, changing its status to "confirmed" and recording the confirmation timestamp.
- **FR-007**: System MUST prevent duplicate confirmed payroll runs for the same client and month combination.
- **FR-008**: System MUST provide a payroll history view listing all past runs with summary statistics (month, status, employee count, total payout).
- **FR-009**: System MUST allow clients to drill into a specific payroll run and view all individual payslips.
- **FR-010**: System MUST allow employees to view their own confirmed payslips (read-only, self-service).
- **FR-011**: System MUST prevent employees from viewing payslips belonging to other employees.
- **FR-012**: System MUST support bilingual (Arabic RTL / English LTR) interfaces for all payroll and payslip views.
- **FR-013**: System MUST allow clients to generate login credentials (email and password) for their employees, creating user accounts with the "employee" role linked to the employee record.

### Key Entities

- **Salary Component**: Represents an individual monetary item (allowance or deduction) attached to an employee. Key attributes: type (allowance/deduction), name, amount. Belongs to exactly one Employee.
- **Payroll Run**: Represents a batch payroll execution for a specific month initiated by a client. Key attributes: month, status (draft/confirmed), execution timestamp. Belongs to exactly one Client. Has many Payslips.
- **Payslip**: Represents an individual employee's compensation record for a specific payroll run. Key attributes: basic salary, total allowances, total deductions, net salary. Belongs to one Payroll Run and one Employee. Captures a point-in-time snapshot of the salary calculation. Has many Payslip Line Items.
- **Payslip Line Item**: Represents a frozen snapshot of a single salary component at the time of the payroll run. Key attributes: component name, type (allowance/deduction), amount. Belongs to one Payslip. Enables itemized display on the employee payslip view and full audit trail.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A client can define salary components for an employee and run payroll for a month with correct net salary calculation in under 5 minutes for up to 50 employees.
- **SC-002**: The system correctly calculates net salary (basic + allowances - deductions) with 100% accuracy, verified by automated tests covering multiple component combinations.
- **SC-003**: 100% tenant isolation is proven via automated tests; no client can view, modify, or run payroll for another client's employees.
- **SC-004**: Employees can view their own payslips within 10 seconds of navigation, and cannot access any other employee's payslip under any circumstances.
- **SC-005**: Payroll runs for up to 200 employees complete within 15 seconds.

## Assumptions

- **Employee Records Exist**: This phase assumes Phase 2 (Employee Management) is complete and employees are already populated in the database with basic salary data.
- **No Pro-Rating**: Salary calculation does not pro-rate for partial months (e.g., mid-month hires). Full monthly components are applied regardless of hire date within the month.
- **No External Payroll Integration**: Payroll data stays within the system. Export to banking or tax systems is out of scope for this phase.
- **No Payroll Reversal**: Once a payroll run is confirmed, it cannot be reversed or deleted. A corrective run for the same month can be handled manually by the client or deferred to a future phase.
- **Employee Login Credentials**: This phase will include the ability for clients to create employee user accounts (role: "employee") as a prerequisite for payslip self-service. This upgrades employees from data-only records to authenticated users.
- **Currency**: All monetary values are in a single currency. Multi-currency support is out of scope.
- **Payslip Snapshot**: Payslips capture salary data at the time of the payroll run. Subsequent edits to salary components do not retroactively update past payslips.
