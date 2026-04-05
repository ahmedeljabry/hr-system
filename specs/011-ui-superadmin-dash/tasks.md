---
description: "Actionable, LLM-friendly task list for Phase 011 Super Admin Dashboard UI/UX refinement."
---

# Tasks: 011-ui-superadmin-dash

**Input**: Design documents from `/specs/011-ui-superadmin-dash/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Organization**: Tasks follow strict user story segregation to maintain isolation and incrementality.

## Phase 1: Setup

**Purpose**: Install required JS dependencies for charts.

- [x] T001 Run `npm install apexcharts` in the project root to pull the charting library dependency. Require it in the primary Alpine/JS bundler file (e.g., `resources/js/app.js` or `resources/js/charts.js`).

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Preparing core API structures and UI components needed across multiple user stories.

- [x] T002 Create the `x-sidebar` component in `resources/views/components/sidebar.blade.php`. Integrate Alpine.js `x-data="{ collapsed: localStorage.getItem('sidebar_collapsed') === 'true' }"` to manage the open/close state logic.
- [x] T003 Refactor `resources/views/layouts/admin.blade.php` to load the new `<x-sidebar>` component in place of the old navigation layout. Apply CSS logical properties to align the sidebar to the `inline-start` edge.

**Checkpoint**: The main Layout layout successfully supports a collapsible sidebar state.

---

## Phase 3: User Story 1 - Trend Sparklines on Dashboard (Priority: P1) 🎯 MVP

**Goal**: Implement the KPI Sparklines utilizing ApexCharts.

**Independent Test**: Load the admin dashboard. Verify 3 KPI cards render dynamic charts.

### Implementation for User Story 1

- [x] T004 [US1] Create the `x-sparkline-card` Blade component in `resources/views/components/sparkline-card.blade.php`. Include Alpine JS `x-init` hooks pointing to ApexCharts to render incoming data dynamically.
- [x] T005 [US1] Update `app/Services/DashboardService.php` to define `getTrendData(string $metric, int $days)`. Implement Eloquent `whereDate` logic fetching values for tenants, users, and employees into the required array format.
- [x] T006 [US1] Update `app/Http/Controllers/Admin/DashboardController.php` to wire `DashboardService::getTrendData` arrays into the view response for the dashboard index.
- [x] T007 [US1] Refactor `resources/views/admin/dashboard.blade.php` to loop/display the `<x-sparkline-card>` components injected with the controller's trend data variables.

**Checkpoint**: Super admin dashboards dynamically highlight trends with sparkline graphs.

---

## Phase 4: User Story 2 - Advanced Data Tables (Priority: P1)

**Goal**: Shift long data tables to scalable server-side pagination with inline Alpine filtering.

**Independent Test**: Load the clients index, check if filtering the name dynamically updates the table data without hard refreshing the main view.

### Implementation for User Story 2

- [x] T008 [P] [US2] Create the `x-data-table` Blade component in `resources/views/components/data-table.blade.php` that implements an Alpine structure capturing filter states. Include debounced watch properties to initiate GET queries toward the server.
- [x] T009 [P] [US2] Create the `x-bulk-action-bar` layout component in `resources/views/components/bulk-action-bar.blade.php` configured to appear via a translated `translate-y` CSS property when checkboxes are clicked.
- [x] T010 [US2] Update `app/Http/Controllers/Admin/ClientController.php` index logic to check for `Accept: application/json` or AJAX requests. Return data directly via `->paginate()` appended with parsed `request()->query()` filters.
- [x] T011 [US2] Refactor `resources/views/admin/clients/index.blade.php` (if exists) replacing the generic tabular view with the interactive `<x-data-table endpoint="{{ route('admin.clients.index') }}">` approach.

**Checkpoint**: Admin resource indexes successfully stream paginated entries and support client filtering.

---

## Phase 5: User Story 3 - Persistent Collapsible Sidebar (Priority: P2)

**Goal**: Guarantee sidebar toggle transitions and cross-navigation retention.

**Independent Test**: Click toggle. Navigate page. Validate sidebar holds state.

### Implementation for User Story 3

- [x] T012 [US3] Modify the Alpine state inside `resources/views/components/sidebar.blade.php` to include a method `toggle()` that sets the JS variable and synchronizes utilizing `localStorage.setItem('sidebar_collapsed', this.collapsed)`. Add logic to smooth width transition (250px to 64px) via CSS duration classes.

**Checkpoint**: Sidebar navigates correctly and holds status locally without backend latency.

---

## Phase 6: User Story 4 - Administrative Notification Badges (Priority: P3)

**Goal**: Attach static KPI counts directly to sidebar navigation anchors.

**Independent Test**: Identify unfulfilled system conditions and confirm red badge indicators display correctly in the Sidebar.

### Implementation for User Story 4

- [x] T013 [P] [US4] Configure `resources/views/components/sidebar.blade.php` icons to conditionally render numeric pill elements if a `$badge` value passed is greater than 0.
- [x] T014 [US4] Update `app/Providers/ViewServiceProvider.php` (or AppServiceProvider) to generate a view composer aimed at `layouts.admin` ensuring the notification static counts (e.g., pending users to approve) are populated globally and passed down to `<x-sidebar>`.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Validating functionality.

- [x] T015 Perform `npm run build` to bind the new ApexCharts dependency alongside Vite builds. 
- [x] T016 Check Arabic RTL direction bindings to ensure sparklines read correctly matching the inline-end layout. 

## Dependencies & Execution Order

- **Phase 2 Foundational** must happen so the general layout runs.
- **US1** and **US2** are primary efforts. They operate in complete silos and can be tackled randomly.
- **US3** directly tweaks Phase 2’s sidebar setup and can be done dynamically whenever Phase 2 completes.
