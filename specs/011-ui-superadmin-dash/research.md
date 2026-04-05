# Technical Research & Decisions: Super Admin Dashboard Overhaul

## Overview
This document consolidates the technical research and architecture choices for implementing the Super Admin Dashboard overhaul (Phase 011).

## 1. Charting Library Selection
- **Decision:** Use ApexCharts for sparkline widgets and trend visualizations.
- **Rationale:** ApexCharts provides lightweight sparkline chart types out-of-the-box, supports responsive sizing, and integrates cleanly with Alpine.js. It has a smaller bundle footprint than Chart.js for sparkline-only use cases and supports RTL layouts natively.
- **Alternatives Considered:** Chart.js (viable but requires more configuration for sparklines), D3.js (overkill for dashboard sparklines — better for complex custom visualizations), inline SVG sparklines (too limited for interactive tooltips).
- **Integration:** Install via `npm install apexcharts`. Initialize charts via Alpine.js `x-init` directives for reactive updates.

## 2. Server-Side Pagination Strategy
- **Decision:** Use Laravel's built-in `->paginate()` with AJAX-based page fetching using Alpine.js `fetch()` calls.
- **Rationale:** Clarification session confirmed server-side pagination with inline AJAX filtering. Laravel's paginator provides `->paginate(25)` with `->appends()` for filter parameters. AJAX requests fetch HTML partials or JSON, updating the table body without full page reload.
- **Alternatives Considered:** Client-side pagination (rejected — doesn't scale for thousands of records), Livewire tables (rejected — adds unnecessary server-round-trip complexity when Alpine.js with partial HTML responses is sufficient).
- **Implementation:** Controllers return paginated JSON when `Accept: application/json` header is present, or partial Blade views via `->fragment('table-body')`.

## 3. Inline Filtering Architecture
- **Decision:** Filters render as input fields within table headers. On input change (debounced 300ms), an Alpine.js handler sends a GET request with filter parameters to the existing controller endpoint.
- **Rationale:** This keeps the filtering logic on the server (Eloquent query scopes), ensuring consistency and security. The debounce prevents excessive requests.
- **Implementation:** Each filterable column gets a text/select input. Alpine tracks filter state as reactive data. On change, fetches `?filter[name]=john&filter[status]=active&page=1`.

## 4. Sidebar Collapse Architecture
- **Decision:** Use a `<x-sidebar>` Blade component with Alpine.js controlling the collapsed/expanded state. State persists in browser localStorage.
- **Rationale:** Clarification session confirmed localStorage persistence without backend storage. Alpine.js reads `localStorage.getItem('sidebar_collapsed')` on init and writes on toggle. CSS transitions handle the animation (width 250px ↔ 64px).
- **Alternatives Considered:** Cookie-based persistence (rejected — unnecessary server-side overhead), database preference (rejected by stakeholder as overkill).
- **RTL Support:** Sidebar anchors to `inline-start`, automatically positioned on the correct side for RTL/LTR.

## 5. Notification Badge Implementation
- **Decision:** Badge counts are rendered server-side in the sidebar Blade template using data passed from the admin layout's view composer or a shared middleware.
- **Rationale:** For Phase 011, notification badges represent static counts (e.g., pending approvals, new tenants). Real-time WebSocket updates are out of scope — counts refresh on each page load.
- **Alternatives Considered:** WebSocket real-time updates (deferred for future — adds infrastructure complexity), client-side polling (unnecessary for admin dashboard refresh patterns).

## 6. Dashboard Metric Data Architecture
- **Decision:** Extend `DashboardService` to provide trend data (last 7/30 day snapshots) alongside current totals.
- **Rationale:** Constitution Principle III requires business logic in services. The service queries aggregated data and returns structured arrays: `['current' => 150, 'trend' => [120, 125, 130, 140, 145, 148, 150]]`.
- **Data Sources:** Tenant count, active user count, active employee count — all from existing Eloquent models with `->whereDate()` range queries.
