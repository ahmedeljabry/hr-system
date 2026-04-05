# Implementation Plan: 013-ui-employee-portal

**Branch**: `013-ui-employee-portal` | **Date**: 2026-04-05 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/013-ui-employee-portal/spec.md`

## Summary
Create a mobile-first, highly accessible employee portal with premium receipt-style payslip views (with print stylesheet), a sliding notification center with badge counts, and touch-optimized navigation. All views use Phase 009 design tokens and support RTL/LTR.

## Technical Context

**Language/Version**: PHP 8.3 & HTML/Blade, JavaScript (Alpine.js)  
**Primary Dependencies**: TailwindCSS, Alpine.js, Design System tokens from Phase 009  
**Storage**: MySQL 8 (existing employee data); potential new `notifications` table for read/unread state  
**Testing**: PHPUnit (payslip rendering, notification endpoints), Laravel Feature Tests  
**Target Platform**: Web Browsers — **Mobile-first** (320px+), responsive to desktop  
**Project Type**: Laravel Monolith (Blade Views)  
**Performance Goals**: Pages load < 1.5s on mobile, payslip print renders without layout shift, notification badge update < 1s  
**Constraints**: CSS Logical Properties, min 44px touch targets, print stylesheet for payslips, WCAG 2.1 AA  
**Scale/Scope**: Employee portal — dashboard, payslips, attendance, leaves, tasks, profile, notifications

## Constitution Check

*GATE: Passed*

- **I. Strict Multi-Tenant Isolation**: All employee data scoped by authenticated employee's `client_id`. Notifications filtered by `employee_id`.
- **II. TDD-First**: Tests for payslip view rendering, print stylesheet output, notification CRUD, and mobile responsive assertions.
- **III. Thin Controllers, Fat Services**: Notification logic (mark read, badge count) in a NotificationService. Payslip display logic stays in existing PayslipService.
- **IV. Bilingual UI First**: All labels use localization keys. CSS logical properties for RTL/LTR. Notification messages localized.
- **V. Eloquent Database Interactions**: All queries via Eloquent. Notification queries use `Notification::where('employee_id', ...)`.

## Project Structure

### Documentation (this feature)

```text
specs/013-ui-employee-portal/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
└── tasks.md             # Via /speckit.tasks
```

### Source Code (repository root)

```text
app/
├── Http/Controllers/Employee/
│   ├── PayslipController.php        # Updated payslip view
│   ├── NotificationController.php   # New — notification CRUD
│   └── DashboardController.php      # Updated for mobile layout
├── Models/
│   └── Notification.php             # New model (if not exists)
├── Services/
│   └── NotificationService.php      # New — badge count, mark read

database/
├── migrations/
│   └── xxxx_create_notifications_table.php  # If needed

resources/
├── views/
│   ├── employee/
│   │   ├── dashboard.blade.php      # Mobile-first redesign
│   │   ├── payslips/
│   │   │   ├── index.blade.php      # Payslip list
│   │   │   └── show.blade.php       # Receipt-style payslip view
│   │   ├── leaves/
│   │   ├── tasks/
│   │   └── profile/
│   ├── components/
│   │   ├── payslip-receipt.blade.php # Receipt-style payslip component
│   │   ├── notification-panel.blade.php # Sliding notification center
│   │   ├── notification-bell.blade.php  # Bell icon with badge count
│   │   ├── mobile-nav.blade.php     # Mobile bottom navigation bar
│   │   └── empty-state.blade.php    # Reusable empty state placeholder
│   └── layouts/
│       └── employee.blade.php       # Mobile-first employee layout
├── css/
│   └── print.css                    # Print-specific stylesheet for payslips
```

**Structure Decision**: Extending existing Laravel employee views. A new `Notification` model and migration may be required if the notification table doesn't exist. Mobile navigation replaces standard sidebar on small viewports.

## Complexity Tracking

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|--------------------------------------|
| New Notification model/migration | Notification center requires read/unread state per employee | Cannot track read state without persistence; localStorage is per-device only and doesn't meet multi-device requirements |
