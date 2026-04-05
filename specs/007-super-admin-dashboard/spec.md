# Feature Specification: Super Admin Dashboard

**Feature Branch**: `007-super-admin-dashboard`  
**Created**: 2026-04-05  
**Status**: Draft  
**Input**: User description: "From Plan.md create specification for Phase 7 — Super Admin Dashboard"

## User Scenarios & Testing

### User Story 1 - System-Wide Stats Dashboard (Priority: P1) 🎯 MVP

When a super admin logs in, they land on an admin dashboard that displays a high-level overview of the entire system: total number of registered clients, total number of employees across all tenants, count of active vs. suspended/expired subscriptions. This gives the super admin an instant health snapshot of the platform without navigating to any sub-section.

**Why this priority**: The stats dashboard is the entry point for every super admin session. It requires only read-aggregation across existing tables and no new entities, making it the fastest path to a functional MVP for this phase. Every other feature in Phase 7 branches from this screen.

**Independent Test**: Log in as super admin, navigate to `/admin/dashboard`, verify the page shows correct counts for total clients, total employees, and subscription status breakdown — sourced from real database data, not hardcoded.

**Acceptance Scenarios**:

1. **Given** the system has 5 clients (3 active, 1 suspended, 1 expired) and 42 employees total, **When** the super admin views the dashboard, **Then** they see widgets showing "5 Clients", "42 Employees", "3 Active", "1 Suspended", "1 Expired" — three separate subscription-status counts, never merged.
2. **Given** a new client registers, **When** the super admin refreshes the dashboard, **Then** the client count increments by 1 and the active subscription count reflects the new client's subscription status.
3. **Given** no clients exist yet, **When** the super admin views the dashboard, **Then** all widget counts show "0" with a friendly empty state.

---

### User Story 2 - Client List with Subscription Management (Priority: P2)

The super admin can view a paginated, sortable table of all registered clients showing each client's company name, subscription status (active / suspended / expired), subscription end date, and employee count. From this list, the super admin can directly change any client's subscription status using an inline dropdown selector in the status column — selecting a new value saves immediately and displays a flash confirmation message, without navigating to a separate page.

**Why this priority**: Subscription management is the primary operational responsibility of the super admin role. Without this capability, the system has no mechanism to enforce billing compliance across tenants. It directly follows the stats dashboard since the client list is the most actionable screen.

**Independent Test**: Log in as super admin, navigate to `/admin/clients`, verify all registered clients appear with correct stats. Change a client's status from "active" to "suspended", refresh the page, verify the status has changed. Verify the suspended client can no longer log into the employee portal (checked via the existing `check_subscription` middleware).

**Acceptance Scenarios**:

1. **Given** 10 clients exist, **When** the super admin visits the clients list, **Then** all 10 clients are displayed with their company name, current subscription status, subscription end date, and employee count.
2. **Given** a client with status "active", **When** the super admin selects "Suspended" from the inline status dropdown, **Then** the client record is updated, the list reflects "Suspended", and a success flash message is displayed.
3. **Given** a client with status "suspended", **When** the super admin selects "Active" from the inline status dropdown, **Then** the client record is updated to "Active" and the client's employees can again access the portal.
4. **Given** the clients table has more than 15 entries, **When** the super admin views the list, **Then** results are paginated (15 per page) with working page navigation.
5. **Given** the super admin clicks a column header (e.g., "Company Name" or "Status"), **When** the sort is applied, **Then** the list reorders accordingly.

---

### User Story 3 - Client Detail: Employee List View (Priority: P3)

The super admin can drill into any client's detail page to view a list of all employees belonging to that client. Each employee row shows: name, position, hire date, and whether they have an associated login account (user account linked or not). This is a read-only view — the super admin does not create or modify employees from this screen.

**Why this priority**: The ability to inspect any tenant's employee roster is essential for support and audit purposes. It is lower priority than subscription management but higher than user editing since it provides transparency into what data exists per tenant.

**Independent Test**: Log in as super admin, navigate to a specific client's detail page (`/admin/clients/{id}`), verify the list shows all employees of that client only — no employees from other clients.

**Acceptance Scenarios**:

1. **Given** Client A has 5 employees and Client B has 3 employees, **When** the super admin navigates to Client A's detail page, **Then** they see exactly 5 employees, none of whom belong to Client B.
2. **Given** a client with no employees, **When** the super admin views their detail page, **Then** they see a friendly empty state ("No employees found").
3. **Given** an employee with a linked user account and one without, **When** the super admin views the employee list, **Then** the linked employee shows a "Has Login" indicator and the other shows "No Login".

---

### User Story 4 - Edit Any User's Basic Info (Priority: P4)

The super admin can edit the name and email address of any user in the system (client account owners or employee accounts). This is restricted to name and email only — the super admin cannot change a user's role, reset their password directly, or delete their account from this interface.

**Why this priority**: This feature handles the practical support scenario where a client or employee contacts the super admin to correct a typo in their name or a changed email address. It is lower priority because it handles edge-case corrections rather than day-to-day operations.

**Independent Test**: Log in as super admin, find a user, update their name and email, verify the changes are saved and reflected in the user's profile on their next login.

**Acceptance Scenarios**:

1. **Given** a user with name "Jhon Doe" and email "jhon@example.com", **When** the super admin corrects the name to "John Doe", **Then** the user's name is updated in the database and visible on their next login.
2. **Given** the super admin attempts to set an email that is already used by another user, **When** the form is submitted, **Then** validation fails with a "Email already taken" message and no changes are saved.
3. **Given** the super admin opens the edit user form, **Then** role, password, and account deletion controls are absent from the form — only name and email fields are present.
4. **Given** the super admin submits the edit form with a blank name, **When** the form is submitted, **Then** validation fails with a "Name is required" message.

---

### Edge Cases

- What happens when a super admin tries to access a client or employee route? — The `role:super_admin` middleware on all admin routes is distinct from `role:client` and `role:employee` middleware groups. Accessing any non-admin route while authenticated as super admin results in an authorization error, not a data view.
- What happens if a client's subscription end date has passed but their status is still "active"? — The system displays the actual status field value. The super admin is responsible for manually updating expired subscriptions. An automatic expiry job is out of scope for Phase 7.
- What if the super admin tries to edit their own account via the edit user form? — Allowed — name and email changes to the super admin's own account are permitted via the same form.
- What happens when there are thousands of clients? — The client list is paginated (15 per page). The stats dashboard aggregations use database-level COUNT queries rather than loading all records into memory.
- Can the super admin see employee documents (national ID, contract images)? — No. Document viewing remains scoped to the employee's own authenticated access. The super admin employee list shows only metadata, not file contents.
- What if an admin action fails mid-write (e.g., DB error during status change)? — The operation must fail cleanly with an error flash message; no partial update is persisted. The log entry is only written after a confirmed successful DB write.
- What happens if two super admins change the same client's status simultaneously? — Last write wins; no concurrency protection is applied. The acting admin always sees a success message reflecting their own change. This is acceptable given the low probability of concurrent super admin sessions.

## Requirements

### Functional Requirements

- **FR-001**: System MUST display a super admin dashboard with system-wide aggregate widgets: total clients, total employees, active subscription count, suspended subscription count, and expired subscription count — five separate counters, all computed at page load from live database data.
- **FR-002**: System MUST provide a paginated (15 per page), sortable list of all registered clients showing company name, subscription status, subscription end date, and employee count.
- **FR-003**: System MUST allow the super admin to change any client's subscription status to any of the three values (active / suspended / expired) via an inline dropdown selector in the client list — the change MUST persist to the database immediately and display a success/failure flash message. No separate page navigation is required.
- **FR-004**: System MUST display a client detail page listing all employees of that client with each employee's name, position, hire date, and login account status — read-only, no edit controls for employee data.
- **FR-005**: System MUST allow the super admin to edit any user's name and email address, with validation ensuring email uniqueness and name non-empty.
- **FR-006**: System MUST restrict all admin routes to users with the `super_admin` role — any other role attempting to access admin routes MUST be rejected with a 403 response.
- **FR-009**: System MUST write an application log entry for every privileged admin action (subscription status change, user name/email edit), recording the acting super admin's user ID, the affected record's ID, the old value, the new value, and a UTC timestamp. Logging MUST use the existing application log file — no new database table is required.
- **FR-007**: System MUST support full bilingual UI (Arabic RTL / English LTR) for all new admin screens using the existing localisation system.
- **FR-008**: System MUST display friendly empty states when no data is available on any admin list or dashboard widget.

### Key Entities

- **AdminStats**: A computed, read-only aggregate — not stored in the database. Derived at request time from counts across the `clients`, `users`, and `employees` tables.
- **Client** *(existing)*: The `clients` table record linked to a user with `role=client`. Relevant fields for this phase: `company_name`, `subscription_status`, `subscription_end`, and its `employees` relationship count.
- **User** *(existing)*: The `users` table record. Only `name` and `email` are editable by the super admin. Role is read-only from the admin interface.
- **Employee** *(existing)*: The `employees` table record. Read-only from the admin interface. Relevant fields: `name`, `position`, `hire_date`, `user_id` (nullable — indicates login account presence).

## Success Criteria

### Measurable Outcomes

- **SC-001**: Super admin can reach any client's subscription toggle in 2 clicks from the admin dashboard.
- **SC-002**: Admin dashboard stats page loads in under 2 seconds with up to 500 clients and 5,000 employees in the system.
- **SC-003**: Zero cross-tenant data leakage — the super admin client detail page shows only employees of the selected client, never mixing data from other tenants.
- **SC-004**: All admin pages render correctly in both Arabic (RTL) and English (LTR) modes without layout breakage.
- **SC-005**: A suspended client's employees are blocked from portal access within one page load after the super admin applies the suspension — no cache delay.

## Assumptions

- Phases 1–6 are implemented and provide the `users`, `clients`, `employees`, `payslips`, `tasks`, `assets`, and `announcements` tables that this phase reads from.
- The super admin account is seeded (via `SuperAdminSeeder` from Phase 1) — there is no self-registration flow for super admins.
- No new database migrations are required for Phase 7. All data is read from existing tables; the only writes are status updates to the `clients` table and name/email updates to the `users` table.
- The existing `RoleMiddleware` (from Phase 1) with the `role:super_admin` binding is already registered and functional; Phase 7 simply creates a new route group protected by it.
- Subscription status toggling is a manual admin action — there is no automatic expiry cron job in scope for this phase.
- The super admin cannot delete clients, employees, or users from the Phase 7 interface — this is explicitly out of scope to avoid accidental data loss.
- The admin views will use a new `layouts/admin.blade.php` sidebar layout consistent in structure with `layouts/employee.blade.php`, adapted for the admin navigation. The sidebar contains exactly two links: **Dashboard** (`/admin/dashboard`) and **Clients** (`/admin/clients`). Client detail and user edit screens are sub-pages reached via row-level navigation, not top-level sidebar entries.
- Password reset for users is out of scope — the super admin edits only `name` and `email`.

## Clarifications

### Session 2026-04-05

- Q: Should the super admin be able to set subscription status to "expired" manually, or only toggle between "active" and "suspended"? → A: The toggle controls three states — active, suspended, and expired. The super admin can set any of the three values from the client list.
- Q: Should the client list be sortable on all columns or only specific ones? → A: Sortable on company name, status, and subscription end date. Employee count sorting is out of scope for Phase 7.
- Q: Should the edit user form be accessible from the client detail page (for that client's user), or only from a global user search? → A: Accessible from the client list (edit the client's owner account) and from the employee list on the client detail page (edit any employee's user account). No global user search in Phase 7.

### Session 2026-04-05 (speckit.clarify)

- Q: Should the dashboard show a combined "Inactive" count (suspended + expired), or separate counts for each subscription status? → A: Show three separate counts — Active, Suspended, and Expired — never merged into a single "Inactive" label.
- Q: How should the subscription status change be presented in the client list UI — dropdown, per-state buttons, or modal? → A: Inline `<select>` dropdown per client row; selecting a new value saves immediately with a flash message, no separate page or modal required.
- Q: Should privileged admin actions (status changes, user edits) be logged for audit purposes? → A: Log to the application log file only (no new DB table) — records acting super admin ID, affected record ID, old value, new value, and UTC timestamp.
- Q: How should concurrent edits by two super admins on the same record be handled? → A: Last write wins — no optimistic locking or conflict detection required.
- Q: Which navigation links should appear in the admin sidebar layout? → A: Two links only — Dashboard (`/admin/dashboard`) and Clients (`/admin/clients`). All other screens (client detail, edit user) are sub-pages reached by row-level navigation.
