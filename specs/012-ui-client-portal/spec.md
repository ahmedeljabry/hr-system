# Feature Specification: Client Portal UI Refinement

**Feature Branch**: `012-ui-client-portal`  
**Created**: 2026-04-05  
**Status**: Draft  
**Input**: User description: "Phase 012 from UI/UX Improvement Master Plan — Client (Tenant) Portal Refinement"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Rich Employee Directory Views (Priority: P1)

As an HR Manager (Client user), I want to toggle between a grid card view and a list table view for the employee directory so I can quickly browse employees visually or find specific records efficiently.

**Why this priority**: The employee directory is the most-accessed feature in the Client portal. Upgrading from a plain table to a rich grid/list toggle creates the most visible premium impact.

**Independent Test**: Can be fully tested by logging in as a Client, navigating to the employee directory, and toggling between grid and list views while verifying all employee data renders correctly in both modes.

**Acceptance Scenarios**:

1. **Given** a Client with 10+ employees, **When** viewing the employee directory in grid mode, **Then** employee cards with avatar, name, department, and status are displayed in a responsive card grid.
2. **Given** the employee directory in grid mode, **When** clicking the "List" toggle, **Then** the view switches to a traditional table layout without page reload.
3. **Given** the employee directory, **When** viewing on a mobile device, **Then** the grid view defaults to a single-column card layout.

---

### User Story 2 - Slide-Over Panel Forms (Priority: P2)

As an HR Manager, I want data-entry forms (like Add Employee or Run Payroll) to appear as slide-over panels instead of full-page navigations so I maintain context of where I am in the application.

**Why this priority**: Reducing context switching improves productivity for HR managers who frequently perform data entry tasks. Slide-over panels are a modern UX pattern that keeps the underlying page visible.

**Independent Test**: Can be fully tested by clicking "Add Employee" and verifying a slide-over panel appears from the right side with the complete form, while the employee list remains visible underneath.

**Acceptance Scenarios**:

1. **Given** the employee list view, **When** clicking "Add Employee", **Then** a slide-over panel animates in from the inline-end side with the employee creation form.
2. **Given** an open slide-over panel, **When** clicking outside the panel or pressing Escape, **Then** the panel closes smoothly and returns focus to the underlying page.
3. **Given** a slide-over panel with unsaved changes, **When** attempting to close, **Then** a confirmation prompt appears to prevent data loss.

---

### User Story 3 - Enhanced Document Upload UX (Priority: P3)

As an HR Manager, I want drag-and-drop file upload areas with progress animations when uploading employee documents so the process feels responsive and modern.

**Why this priority**: Document management is a secondary workflow, but the upload experience is currently basic. A premium drag-and-drop interface with visual progress improves perceived quality.

**Independent Test**: Can be fully tested by navigating to an employee's files section, dragging a file onto the upload area, and observing the upload progress animation.

**Acceptance Scenarios**:

1. **Given** an employee file upload section, **When** dragging a file over the upload zone, **Then** the zone visually highlights with a drop indicator.
2. **Given** a file dropped onto the upload zone, **When** the upload begins, **Then** a progress bar animation shows the upload percentage in real-time.
3. **Given** a completed upload, **When** the file is saved, **Then** a success animation plays and the file appears in the list without page reload.

### Edge Cases

- What happens when toggling grid/list view with no employees (empty state)?
- How does the slide-over panel behave on mobile viewports where there's insufficient width?
- What happens when uploading a file that exceeds the server's max upload size?
- How do long employee names or missing avatars render in the grid card view?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a toggle control to switch between grid (card) and list (table) views for the employee directory.
- **FR-002**: Employee grid cards MUST display avatar (or initials fallback), full name, department, and employment status.
- **FR-003**: System MUST render data-entry forms (Add Employee, Run Payroll) as slide-over panels animated from the inline-end side instead of full-page navigations.
- **FR-004**: Slide-over panels MUST include close-on-outside-click, Escape key dismissal, and unsaved-changes confirmation.
- **FR-005**: System MUST support drag-and-drop file uploads with a visual drop zone indicator and real-time progress bar animation.
- **FR-006**: All Client portal views MUST use CSS logical properties for RTL/LTR bilingual compatibility.
- **FR-007**: Grid/list view preference MUST persist across page navigations within the same session.

### Key Entities

- **Employee Card**: Visual representation of an employee record in grid view — avatar, name, department, status badge.
- **Slide-Over Panel**: Reusable overlay container component for contextual forms and detail views.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: HR Managers can toggle between grid and list views with zero page reloads and under 200ms transition time.
- **SC-002**: Slide-over panel forms fully replace full-page navigations for Add Employee, Edit Employee, and Run Payroll actions.
- **SC-003**: File uploads show real-time progress and the uploaded file appears in the list without a page refresh.
- **SC-004**: All Client portal views render correctly in both Arabic (RTL) and English (LTR) modes with zero layout breaks.

## Assumptions

- The backend controllers and endpoints for employee CRUD, payroll, and file upload already exist; this is a UI/UX-only iteration.
- The Design System foundation from Phase 009 (HSL tokens, premium typography, atomic components) is available and will be consumed.
- Alpine.js will handle client-side interactivity (view toggle state, panel open/close, drag-and-drop events).
- Employee avatars may not exist for all employees; an initials-based fallback is required.
