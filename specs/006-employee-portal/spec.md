# Feature Specification: Employee Portal

**Feature Branch**: `006-employee-portal`  
**Created**: 2026-04-05  
**Status**: Draft  
**Input**: User description: "From Plan.md create specification for Phase 6 — Employee Portal"

## User Scenarios & Testing

### User Story 1 - Employee Dashboard with Summary Widgets (Priority: P1) 🎯 MVP

When an employee logs in, they land on a personalised dashboard that shows at-a-glance summary widgets: their leave balance, number of pending/in-progress tasks, recent payslip, and upcoming due dates. This replaces the current placeholder dashboard with real, data-driven widgets.

**Why this priority**: The dashboard is the first thing every employee sees after login. It aggregates data from all other modules, making it the highest-impact screen. Without it, employees must navigate to each section individually.

**Independent Test**: Log in as an employee, verify the dashboard shows correct counts for leave balance, pending tasks, assigned assets, and latest payslip amount — all scoped to the logged-in employee only.

**Acceptance Scenarios**:

1. **Given** an employee with 3 pending tasks and 12 remaining leave days, **When** they log in and view the dashboard, **Then** they see a tasks widget showing "3 Pending" and a leave widget showing "12 Days Remaining".
2. **Given** an employee with a confirmed payslip for the current month, **When** they view the dashboard, **Then** they see the latest payslip net salary in a summary card.
3. **Given** an employee with 2 assigned assets, **When** they view the dashboard, **Then** they see an assets widget showing "2 Assets Assigned".
4. **Given** an employee with no data (new hire), **When** they view the dashboard, **Then** they see friendly empty states for each widget with zero counts.

---

### User Story 2 - Employee Profile View (Priority: P2)

An employee can view their own profile information including their name, position, hire date, and securely access their uploaded documents (national ID image, contract image). Documents are served through authenticated, tenant-scoped file access — never through public URLs.

**Why this priority**: Employees need to verify their personal records and access their own documents without contacting HR. This is a core self-service feature that reduces support burden.

**Independent Test**: Log in as an employee, navigate to "My Profile", verify personal info is displayed and document images (national ID, contract) are viewable through secure URLs.

**Acceptance Scenarios**:

1. **Given** an employee with uploaded national ID and contract images, **When** they visit their profile page, **Then** they see their personal details and can view both document images.
2. **Given** an employee without uploaded documents, **When** they visit their profile page, **Then** they see their personal details and a message indicating no documents are available.
3. **Given** an employee trying to access another employee's profile URL, **When** the request is processed, **Then** access is denied (the system only allows viewing one's own profile).

---

### User Story 3 - Company Announcements Feed (Priority: P3)

Clients can create and publish announcements for their company (title, body, publish date). Employees see a chronological feed of announcements from their company on their dashboard and on a dedicated announcements page. This enables one-way company-to-employee communication.

**Why this priority**: Announcements are a new entity that requires a migration and new controllers for both Client and Employee roles. It is lower priority because the other portal features (dashboard, profile) leverage already-existing data.

**Independent Test**: A client creates an announcement. An employee of that client logs in and sees the announcement in their feed. An employee of a different client does NOT see it.

**Acceptance Scenarios**:

1. **Given** a client with 3 published announcements, **When** their employee views the announcements page, **Then** all 3 announcements are displayed in reverse chronological order.
2. **Given** a client creates a new announcement, **When** an employee refreshes the dashboard, **Then** the latest announcement appears in the announcements widget on the dashboard.
3. **Given** two clients each with their own announcements, **When** an employee of Client A views announcements, **Then** they see only Client A's announcements, never Client B's.
4. **Given** a client creates an announcement and later deletes it, **When** the employee refreshes the page, **Then** the deleted announcement no longer appears.

---

### User Story 4 - Leave Balance Display & Request History (Priority: P4)

Employees can view their current leave balance breakdown by leave type and see a history of their past leave requests with statuses. This is a read-only view of data managed by the Leave Management module (Phase 4 — to be implemented separately).

**Why this priority**: This story depends on Phase 4 (Leave Management) being built first. However, the portal UI and routing can be scaffolded now so that it's ready when the leave module is integrated.

**Independent Test**: Given leave balance and request data exists for an employee, when they navigate to "My Leaves", they see their remaining balance per leave type and a table of past requests.

**Acceptance Scenarios**:

1. **Given** an employee with leave balance data, **When** they visit the leaves page, **Then** they see a breakdown of remaining days per leave type.
2. **Given** an employee with 5 past leave requests, **When** they visit the leaves page, **Then** they see all 5 requests with date ranges, types, and statuses (approved/rejected/pending).
3. **Given** an employee with no leave data, **When** they visit the leaves page, **Then** they see a friendly empty state indicating no leave records found.

---

### Edge Cases

- What happens when an employee's linked `user_id` is null (employee record exists but no login account)? — Only employees with user accounts can access the portal; accounts without `user_id` cannot log in.
- What happens when a client's subscription expires? — Employee portal access is fully blocked. The `check_subscription` middleware must be applied to all employee routes, redirecting to a "Contact your employer" notice page.
- What happens when document images were uploaded before the secure file serving was implemented? — The profile page gracefully handles missing files by showing a "Document not available" placeholder.
- How are announcements displayed when there are hundreds? — Announcements are paginated (10 per page) to prevent performance degradation.

## Requirements

### Functional Requirements

- **FR-001**: System MUST display a personalised employee dashboard with real-time summary widgets for tasks, assets, leave balance, and latest payslip.
- **FR-002**: System MUST allow employees to view their own profile information including name, position, hire date, and basic salary.
- **FR-003**: System MUST serve employee document images (national ID, contract) through authenticated, tenant-scoped file access — not public URLs.
- **FR-004**: System MUST allow clients to create, edit, and delete company announcements with a title, body, and publication date.
- **FR-005**: System MUST display company announcements to employees in reverse chronological order, scoped to their tenant only.
- **FR-006**: System MUST paginate announcements (10 per page) for both client management and employee viewing.
- **FR-007**: System MUST show employee leave balance breakdown by type and leave request history on a dedicated page.
- **FR-008**: System MUST ensure strict tenant isolation — an employee can only see data belonging to their own company.
- **FR-011**: System MUST enforce client subscription checks on all employee portal routes — employees of clients with expired/suspended subscriptions are blocked from all portal access and redirected to a notice page.
- **FR-009**: System MUST support full bilingual UI (Arabic RTL / English LTR) for all new employee portal screens using the existing localisation system.
- **FR-010**: System MUST display friendly empty states when no data is available for any widget or list.
- **FR-012**: System MUST provide a flat sidebar navigation for employees with direct links to all 7 portal sections: Dashboard, Profile, Payslips, Leaves, Tasks, Assets, and Announcements — all visible at all times.
- **FR-013**: Announcement body content MUST be stored and displayed as plain text with line breaks preserved — no rich text, HTML, or markdown rendering.

### Key Entities

- **Announcement**: A company-wide message created by a client, containing a title, plain-text body (line breaks preserved), and publication timestamp. Scoped to `client_id`. Displayed to all employees of that client.
- **Employee Profile**: An aggregated view of the existing `employees` table data plus secure document access. No new entity — leverages existing `Employee` model.
- **Dashboard Widgets**: Computed, read-only aggregations of existing entities (tasks count, assets count, leave balance, latest payslip). No new entity — computed at request time.

## Success Criteria

### Measurable Outcomes

- **SC-001**: Employees can access all portal sections (dashboard, profile, payslips, tasks, assets, announcements) within 2 clicks from login.
- **SC-002**: Dashboard page loads with all summary widgets in under 3 seconds for an employee with up to 100 records across all modules.
- **SC-003**: Zero cross-tenant data leakage — employees never see data from another company in any portal section.
- **SC-004**: All portal pages render correctly in both Arabic (RTL) and English (LTR) modes without layout breakage.
- **SC-005**: Client can create and publish an announcement, and all employees of that client can see it within one page refresh.

## Assumptions

- Phases 1–5 (Foundation, Employees, Payroll, Leave, Attendance/Tasks/Assets) are already implemented and provide the data that the employee portal aggregates.
- Phase 4 (Leave Management) may not yet be fully implemented; the leave balance and history views will gracefully handle missing tables/data with empty states until that phase is complete.
- The existing `EmployeeFileController` with tenant-scoped file serving (already in `routes/client.php`) will be reused or adapted for employee-side document access.
- Employee accounts are created by clients — employees do not self-register. Only employees with a linked `user_id` can log in.
- The existing `layouts/app.blade.php` navigation will be extended with links for the new portal sections.
- Announcements are text-only (no file attachments) for the initial implementation.

## Clarifications

### Session 2026-04-05

- Q: Should employees be blocked from the portal when their client's subscription expires? → A: Yes — block all portal access and redirect to a "Contact your employer" notice page.
- Q: How should employee portal navigation be structured? → A: Flat sidebar with all 7 sections (Dashboard, Profile, Payslips, Leaves, Tasks, Assets, Announcements) as direct links visible at all times.
- Q: What format should announcement body content use? → A: Plain text with line breaks preserved (no rich text or markdown).
