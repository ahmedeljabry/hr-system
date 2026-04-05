# Implementation Plan: 012-ui-client-portal

**Branch**: `012-ui-client-portal` | **Date**: 2026-04-05 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/012-ui-client-portal/spec.md`

## Summary
Refine the Client (Tenant) HR Manager portal with a grid/list toggle for the employee directory, slide-over panel forms replacing full-page navigations, and enhanced drag-and-drop file uploads with progress animations. All views use Phase 009 design tokens and support RTL/LTR.

## Technical Context

**Language/Version**: PHP 8.3 & HTML/Blade, JavaScript (Alpine.js)  
**Primary Dependencies**: TailwindCSS, Alpine.js, Design System tokens from Phase 009  
**Storage**: MySQL 8 (existing employee/payroll data); Browser localStorage (view toggle preference)  
**Testing**: PHPUnit (view rendering, file upload endpoints), Laravel Feature Tests  
**Target Platform**: Web Browsers (Desktop & Mobile)  
**Project Type**: Laravel Monolith (Blade Views)  
**Performance Goals**: View toggle < 200ms, slide-over panel animation < 300ms, file upload progress real-time  
**Constraints**: CSS Logical Properties, existing CRUD endpoints preserved, Alpine.js-only client-side logic  
**Scale/Scope**: Client portal views — employee directory, employee forms, payroll forms, document uploads

## Constitution Check

*GATE: Passed*

- **I. Strict Multi-Tenant Isolation**: All employee queries scoped by `client_id` via existing middleware. No cross-tenant data visible.
- **II. TDD-First**: Tests for view rendering in both grid/list modes, slide-over panel presence, and file upload progress endpoint.
- **III. Thin Controllers, Fat Services**: No new business logic — existing services handle CRUD. Controller methods remain thin.
- **IV. Bilingual UI First**: All labels use localization keys. CSS logical properties for RTL/LTR layout.
- **V. Eloquent Database Interactions**: All queries via Eloquent. Employee directory uses existing `Employee::query()`.

## Project Structure

### Documentation (this feature)

```text
specs/012-ui-client-portal/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
└── tasks.md             # Via /speckit.tasks
```

### Source Code (repository root)

```text
resources/
├── views/
│   ├── client/
│   │   ├── employees/
│   │   │   ├── index.blade.php         # Grid/list toggle directory
│   │   │   ├── _grid-card.blade.php    # Employee grid card partial
│   │   │   └── _list-row.blade.php     # Employee list row partial
│   │   ├── payroll/
│   │   │   └── run.blade.php           # Modified to use slide-over
│   │   └── ...
│   └── components/
│       ├── slide-over.blade.php        # Reusable slide-over panel
│       ├── view-toggle.blade.php       # Grid/list toggle buttons
│       ├── avatar.blade.php            # Employee avatar with initials fallback
│       ├── drop-zone.blade.php         # Drag-and-drop file upload zone
│       └── upload-progress.blade.php   # File upload progress bar
```

**Structure Decision**: Extending the existing Client views directory. New reusable components added to the shared components directory. Grid/list partials enable Alpine-driven view switching without page reload.

## Complexity Tracking

*No constitution violations present. Standard presentation layer pattern followed.*
