# Feature Specification: Client Dashboard & Employee Management

**Feature Branch**: `002-employee-management`
**Created**: 2026-04-04
**Status**: Draft
**Input**: User description: "Phase 2 — Client Dashboard & Employee Management"

## Clarifications

### Session 2026-04-04

- Q: How should the system handle the requirement for image files during an Excel bulk import? → A: Option B - Add a text `national_id_number` field. Make image files optional during both manual creation and imports, allowing clients to attach them later.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Employee CRUD Management (Priority: P1)

As a client, I want to manage my company's employees (add, edit, delete, list) so that I can maintain an accurate record of my workforce securely.

**Why this priority**: Managing employees is the core functionality of the HR system. All future payroll and attendance modules depend on having a populated list of active employees tied to the client's tenant.

**Independent Test**: Can be fully tested by a client user navigating to the "Employees" section, adding an employee manually with required fields and file uploads, viewing that employee in a paginated list, updating their salary, and finally deleting their record.

**Acceptance Scenarios**:

1. **Given** a logged-in client on the "Employees" page, **When** they click "Add Employee" and fill in name, position, national ID number, basic salary, and (optionally) national ID/contract images, **Then** a new employee record is created explicitly linked to their `client_id` and the files (if provided) are securely stored on a private disk.
2. **Given** a logged-in client, **When** they view the employee list, **Then** they only see employees belonging to their own company, preventing cross-tenant data leakage.
3. **Given** a logged-in client, **When** they update an employee's salary or position, **Then** the record reflects the new data immediately.
4. **Given** a logged-in client, **When** they delete an employee, **Then** the employee record is removed from their list.

---

### User Story 2 - Bulk Employee Import via Excel (Priority: P1)

As a client with many employees, I want to upload an `.xlsx` file to import my workforce in bulk so that I save time compared to manual entry.

**Why this priority**: Clients migrating from other systems or spreadsheets expect a bulk upload tool to overcome the data-entry friction barrier.

**Independent Test**: Can be fully tested by providing a valid `.xlsx` file mapping columns (name, position, national_id, basic_salary, hire_date) and viewing the success metric of rows imported vs. failed.

**Acceptance Scenarios**:

1. **Given** a logged-in client on the import page, **When** they upload a properly formatted `.xlsx` file, **Then** the system parses the file and inserts all valid employees linked to their `client_id`.
2. **Given** a logged-in client uploading a file, **When** the file contains duplicate or invalid data (e.g., negative salary), **Then** the system skips or rejects the invalid rows and presents a clear error report in Arabic/English.

---

### User Story 3 - Dashboard Overview & Subscription Banner (Priority: P2)

As a client, I want to see an overview of my company (dashboard) immediately upon login, including a warning if my subscription is nearing expiration.

**Why this priority**: The dashboard is the landing area, but the data is less critical than the underlying employee CRUD. The subscription banner drives renewals proactively.

**Independent Test**: Can be fully tested by logging in as a client with varying subscription dates to verify the visual display and warnings.

**Acceptance Scenarios**:

1. **Given** a logged-in client, **When** they access their dashboard, **Then** they see quick metrics (e.g., total registered employees).
2. **Given** a client whose subscription expires in less than 7 days, **When** they view the dashboard, **Then** a prominent warning banner is displayed alerting them to renew soon.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow clients to view a paginated, searchable table of their employees.
- **FR-002**: System MUST allow clients to create, read, update, and delete (CRUD) employee records.
- **FR-003**: System MUST securely store file uploads (if provided) for "National ID image" and "Contract image" per employee on a private, non-publicly accessible disk.
- **FR-004**: System MUST strictly isolate all employee queries by the currently authenticated user's `client_id`.
- **FR-005**: System MUST allow clients to upload an `.xlsx` file containing employee data (Name, Position, National ID Number, Salary, Hire Date) and process it in bulk.
- **FR-006**: System MUST validate bulk import data and provide user-friendly feedback on formatting errors.
- **FR-007**: System MUST provide a client dashboard displaying key HR metrics and the active subscription status.
- **FR-008**: System MUST display an expiry warning banner on the dashboard if the client's subscription is close to expiration.
- **FR-009**: System MUST support bilingual (Arabic RTL/English LTR) interfaces for all new views and error messages.

### Key Entities

- **Employee**: Represents a worker hired by a Client. Key attributes: Name, Position, National ID Number (Text, unique per client), National ID image path (Optional), Contract image path (Optional), Basic Salary, Hire Date. Strongly bound to exactly ONE `Client` (tenant).
- **Client** (Existing): The tenant account. Holds the subscription status data which dictates the dashboard expiry warnings.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A client can navigate to the employee management page and manually add a new employee with files in under 2 minutes.
- **SC-002**: The system successfully processes bulk `.xlsx` imports of up to 500 rows within 10 seconds.
- **SC-003**: 100% tenant isolation is proven via automated tests; under no circumstances can Client A query, edit, or delete Client B's employees.
- **SC-004**: All file assets (contracts, ID cards) resolve to private URLs that deny access to unauthenticated or unauthorized users in 100% of checks.

## Assumptions

- **Tenant Scale**: We assume the maximum number of employees per client hitting the import function at once does not exceed typical SME limits (e.g., ~1000 rows). Advanced queueing (jobs) may not be strictly necessary for imports under 500 rows, but memory limits will be handled gracefully.
- **File Storage**: Uploaded files will use the local `storage/app/private` disk. We assume cloud storage (S3) is not required for this phase, but the storage architecture uses Laravel's abstracted `Storage` facade.
- **Employee Accounts**: In Phase 2, employees are merely structured records. They are NOT yet given login credentials (users table) — this comes in a later phase.
- **Subscription Notification**: The "nearing expiration" threshold is assumed to be 7-14 days. No external emails are sent yet; it is purely an in-app banner widget.
