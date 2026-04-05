---
description: "Super Admin Dashboard Overhaul"
---

# Specification: Phase 011 - Super Admin Dashboard Overhaul

## Overview

The Super Administrative dashboard currently serves basic data via standard tables and functional metrics. This phase focuses on elevating the data visualization and management capabilities. By introducing rich visualization widgets (sparklines, trend charts), advanced data manipulation tables, and an intuitive, collapsible navigation system, the Super Admin experience will become more analytical and efficient.

## Clarifications

### Session 2026-04-05
- Q: How should advanced data tables handle "thousands of records" for filtering and display? → A: Server-side pagination with inline AJAX filtering — Load pages of records; filters trigger server requests
- Q: Should the Super Admin sidebar collapsed/expanded state persist across browser sessions? → A: Browser localStorage — persist state per device, no backend needed

## User Scenarios

*   As a Super Admin, I want to see visual trend lines (sparklines) on my Key Performance Indicator (KPI) cards so that I can immediately gauge platform health (e.g., tenant growth, active users) without running reports.
*   As a Super Admin, I want advanced data tables that support inline filtering, sticky headers, and bulk actions so I can manage thousands of records seamlessly.
*   As a Super Admin, I want a modernized, collapsible sidebar with active-state tracking and notification indicators, optimizing my screen real estate.

## Functional Requirements

1.  **Analytical Widgets:** Refactor existing metric cards to incorporate trend sparklines (visual line or bar charts indicating recent historical patterns).
2.  **Advanced Data Tables:** Upgrade standard HTML tables across the Super Admin portal to use server-side pagination with inline AJAX filtering. Tables must include:
    *   Server-side paginated record loading (pages of records fetched on demand).
    *   Inline multi-column filtering interfaces that trigger server-side requests.
    *   Sticky header support during downward scrolling.
    *   Floating bulk-action bars when multiple table rows are selected.
3.  **Navigation Redesign:** Overhaul the sidebar/navbar layout. The navigation must support an expanded and collapsed (icon-only) state. The user's collapse/expand preference MUST persist across browser sessions via browser localStorage (per-device, no backend storage required).
4.  **Notification & State Indicators:** Implement notification badges on relevant sidebar links and ensure the active page is explicitly highlighted in the navigation tree.
5.  **Bilingual Support:** Ensure all charts, tables, and collapsible sidebars honor RTL/LTR logical properties based on the selected language.

## Success Criteria

*   Super Admin KPI widgets successfully display historical trends visually (charts/sparklines) alongside raw numbers.
*   Data tables retain their header visibility when a user scrolls down a list containing more than 50 items.
*   Paginated data tables load each page within 1 second for datasets up to 10,000 records, and inline filter results return within 1 second.
*   The sidebar navigation can be collapsed to increase screen real estate, without losing icon-based accessibility.

## Assumptions

*   A graphing library (e.g., Chart.js, ApexCharts) will be integrated into the frontend bundle.
*   Tenant and system metric data is already available via backend controllers or will be formatted appropriately to feed the new aesthetic widgets.
*   Server-side pagination endpoints will be implemented or extended in existing controllers to support AJAX filter requests.
*   Browser localStorage is available and sufficient for persisting UI state preferences (sidebar collapse state); no cross-device sync is required.
