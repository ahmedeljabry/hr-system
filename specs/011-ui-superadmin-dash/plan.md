# Implementation Plan: 011-ui-superadmin-dash

**Branch**: `011-ui-superadmin-dash` | **Date**: 2026-04-05 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/011-ui-superadmin-dash/spec.md`

## Summary
Elevate the Super Admin dashboard with trend sparkline widgets (via Chart.js/ApexCharts), server-side paginated data tables with inline AJAX filtering, a collapsible sidebar with localStorage persistence, notification badges, and full RTL/LTR bilingual support.

## Technical Context

**Language/Version**: PHP 8.3 & HTML/Blade, JavaScript (Alpine.js)  
**Primary Dependencies**: TailwindCSS, Alpine.js, ApexCharts (charting), Design System tokens from Phase 009  
**Storage**: MySQL 8 (existing data — tenant metrics, user counts); Browser localStorage (sidebar state)  
**Testing**: PHPUnit (controller JSON responses, pagination), Laravel Browser Kit (widget rendering)  
**Target Platform**: Web Browsers (Desktop-focused, responsive)  
**Project Type**: Laravel Monolith (Blade Views + AJAX endpoints)  
**Performance Goals**: Dashboard load < 2s, paginated table page loads < 1s, chart render < 500ms  
**Constraints**: Server-side pagination for datasets > 50 records, CSS Logical Properties, WCAG 2.1 AA  
**Scale/Scope**: Super Admin dashboard + all admin data tables (clients, users, reminder phrases)

## Constitution Check

*GATE: Passed*

- **I. Strict Multi-Tenant Isolation**: Super Admin views cross-tenant data intentionally (system-wide oversight). Individual tenant data scoped by `client_id` where applicable.
- **II. TDD-First**: Tests for pagination endpoints, JSON responses for chart data, and widget rendering.
- **III. Thin Controllers, Fat Services**: Dashboard metric aggregation MUST be in DashboardService. Controllers return data only.
- **IV. Bilingual UI First**: All labels use localization keys. Charts and sidebar honor RTL via CSS logical properties.
- **V. Eloquent Database Interactions**: All paginated queries use Eloquent `->paginate()`. No raw SQL.

## Project Structure

### Documentation (this feature)

```text
specs/011-ui-superadmin-dash/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
└── tasks.md             # Via /speckit.tasks
```

### Source Code (repository root)

```text
app/
├── Http/Controllers/Admin/
│   └── DashboardController.php     # Add chart data endpoints, pagination
├── Services/
│   └── DashboardService.php        # Metric aggregation, trend data queries

resources/
├── views/
│   ├── admin/
│   │   └── dashboard.blade.php     # KPI sparkline widgets, paginated tables
│   ├── components/
│   │   ├── sparkline-card.blade.php # Reusable KPI card with embedded chart
│   │   ├── data-table.blade.php    # Server-side paginated table wrapper
│   │   └── sidebar.blade.php       # Collapsible sidebar with localStorage
│   └── layouts/
│       └── app.blade.php           # Sidebar integration
├── js/
│   └── charts.js                   # ApexCharts initialization helpers
```

**Structure Decision**: Extending existing Laravel structure. Chart library integrated via npm. Sidebar component replaces existing navigation markup in the admin layout. DashboardService aggregates metrics (Constitution Principle III).

## Complexity Tracking

*No constitution violations present. DashboardService already exists and will be extended for trend data.*
