---
description: "Actionable, LLM-friendly task list for Phase 013 Employee Portal UX/UI refinement."
---

# Tasks: 013-ui-employee-portal

**Input**: Design documents from `/specs/013-ui-employee-portal/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Organization**: Tasks follow strict user story segregation to maintain isolation and incrementality.

## Phase 1: Setup

**Purpose**: Database and infrastructure alignments explicit to Employee notifications.

- [x] T001 Run `php artisan make:migration create_notifications_table` to establish the new table architecture defined in `data-model.md`.
- [x] T002 Implement the DB schema in the new Notification migration matching fields: `employee_id`, `type`, `title`, `message`, `read_at`, `related_type`, `related_id`. Run `php artisan migrate`.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core application layers providing required UI states for the user stories.

- [x] T003 Create `app/Models/Notification.php` utilizing standard relationships (`employee()`, `related()`). Establish query scopes for `scopeUnread` and `scopeForEmployee`.
- [x] T004 Create `app/Services/NotificationService.php` adding methods required to get totals (`getUnreadCount()`), query entries (`getNotifications()`), and mutate status (`markAsRead()`, `createNotification()`).
- [x] T005 Create `resources/views/components/empty-state.blade.php`. Bind layout patterns to showcase icons matching the Phase 009 Design System with an explicit Title and Sub-description.

**Checkpoint**: Central messaging models and blank states are accessible globally.

---

## Phase 3: User Story 1 - Mobile-First Dashboard & Navigation (Priority: P1) 🎯 MVP

**Goal**: Convert desktop navigation hierarchies to a bottom-nav overlay geared for smaller screens.

**Independent Test**: Preview dashboard rendering on a 390px mobile viewport format looking explicitly for standard 44px min-touch areas without lateral breaks.

### Implementation for User Story 1

- [x] T006 [P] [US1] Create the core mobile shell: `resources/views/components/mobile-nav.blade.php`. Anchor the UI container to the base edge using CSS position attributes `fixed bottom-0 w-full` hiding visibility conditionally on `md:` breakpoints. Define the 5 core module links inside.
- [x] T007 [P] [US1] Refactor `resources/views/layouts/employee.blade.php` to actively ingest the `<x-mobile-nav>` component outside of the `<main>` scrollable window.
- [x] T008 [US1] Modify `resources/views/employee/dashboard.blade.php`. Convert any inflexible absolute tables into stacked cards mapping to exact Tailwind constraints optimizing for single-column rendering without horizontal overflow. Load `<x-empty-state>` for grids with 0 entries.

**Checkpoint**: The employee space functions fluidly mimicking a native app form factor.

---

## Phase 4: User Story 2 - Premium Payslip View (Priority: P1)

**Goal**: Emulate visual print receipts emphasizing net pay clarity.

**Independent Test**: Load an employee payslip view. Hit CTRL-P. Look at the isolated A4 rendering format.

### Implementation for User Story 2

- [x] T009 [P] [US2] Create a standalone CSS print file `resources/css/print.css`. Map direct overrides targeting `nav, footer, .no-print` elements to `display: none!important;`. Include this style tag conditionally within `<head>` for payslip outputs.
- [x] T010 [US2] Create `resources/views/components/payslip-receipt.blade.php`. Generate a structurally dense output displaying the Corporate Brand mapping specific sections for Earnings, Deductions, and overriding Net Value highlights. Incorporate Alpine JS specifically hiding nested tables until mobile accordions are clicked.
- [x] T011 [US2] Update `resources/views/employee/payslips/show.blade.php`. Wrap the entire request via `<x-payslip-receipt>` and wire in the local data model variables mapped from the Payslip controller.

**Checkpoint**: Financial documents are pristine and exportable dynamically.

---

## Phase 5: User Story 3 - Notification Center (Priority: P2)

**Goal**: Deliver a universal notification slide-out mechanism.

**Independent Test**: Validate counting algorithms display red integers over the Notification Bell component. Interacting displays the sidebar payload.

### Implementation for User Story 3

- [x] T012 [P] [US3] Create `resources/views/components/notification-bell.blade.php`. Produce the core bell icon implementing a red dynamic indicator pill passing the count prop. Bind Alpine JS click event `$dispatch('toggle-notifications')`.
- [x] T013 [P] [US3] Create `resources/views/components/notification-panel.blade.php` utilizing standard translateX animations tied to the `@toggle-notifications.window` listener to render the interface above the app layout.
- [x] T014 [US3] Update `app/Http/Controllers/Employee/DashboardController.php` (or implement a standard ViewComposer) to invoke `NotificationService::getUnreadCount($auth->user()->employee->id)` and hand the data globally to the employee blade layout frame.
- [x] T015 [US3] Finalize `resources/views/layouts/employee.blade.php` incorporating the dynamically bound `<x-notification-bell>` and the overarching `<x-notification-panel>` components linking to actual query payloads.

**Checkpoint**: Information discovery is instantaneous and globally cohesive.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Test execution, RTL validations, and cleanup.

- [x] T016 Execute `npm run build` rendering Tailwind dependencies and print.css bindings. 
- [x] T017 Map all core elements of `<x-empty-state>` and `<x-payslip-receipt>` strings utilizing standard laravel `__('text')` keys binding heavily to correct inline localization variables (en.json, ar.json).

## Dependencies & Execution Order

- **Phase 1 & 2** are strict structural prerequisites introducing models.
- **US1** and **US2** touch decoupled UI paths and can execute asynchronously.
- **US3** is dependent immediately upon the DB Models mapped in Phase 1 & 2 logic layers.
