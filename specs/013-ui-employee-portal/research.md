# Technical Research & Decisions: Employee Portal & Micro-Interactions

## Overview
This document consolidates the technical research and architecture choices for implementing the Employee Portal mobile-first overhaul (Phase 013).

## 1. Mobile-First Layout Strategy
- **Decision:** Use a mobile-first CSS approach with `min-width` media queries to progressively enhance for larger screens. Default styles target 320px–480px viewports.
- **Rationale:** Employees are the largest user group and primarily interact via mobile devices. Starting with mobile layout ensures the core experience is optimized, then enhanced for desktop.
- **Implementation:** Single-column layout by default. At 768px+, layouts expand to two-column grids. At 1024px+, full desktop multi-column layouts. All spacing uses `rem` units for consistent scaling.
- **Alternatives Considered:** Desktop-first with responsive shrinking (rejected — leads to cramped mobile UX), separate mobile app (rejected — out of scope, web-based is sufficient).

## 2. Mobile Navigation Pattern
- **Decision:** Bottom navigation bar on mobile (< 768px) with 4-5 primary action icons (Dashboard, Payslips, Leaves, Tasks, Profile). Standard sidebar on desktop.
- **Rationale:** Bottom navigation is the most thumb-friendly pattern for mobile web apps and mimics native app navigation. Users can reach all primary functions without stretching. Each icon has a 44px minimum touch target per WCAG guidelines.
- **Alternatives Considered:** Hamburger slide-out menu (rejected — hides navigation behind an extra tap, reducing discoverability), top tab bar (rejected — takes up precious vertical space on mobile).

## 3. Receipt-Style Payslip Architecture
- **Decision:** Create a `<x-payslip-receipt>` Blade component that renders payslip data in a structured receipt layout with clear visual sections.
- **Rationale:** Payslips are the most important employee-facing document. A well-structured receipt layout improves readability and trust. The component receives a `$payslip` Eloquent model and renders all sections.
- **Layout Structure:**
  1. Company header (logo, name, address)
  2. Pay period and employee info bar
  3. Earnings breakdown table (base salary, allowances, overtime)
  4. Deductions breakdown table (taxes, insurance, loans)
  5. Net salary highlight (large, prominent number)
  6. Footer with payslip ID and generation date
- **Print Optimization:** A dedicated `print.css` stylesheet hides all navigation, buttons, and UI chrome. The receipt renders as a clean A4-proportioned document. `@media print` rules ensure proper page breaks and margins.
- **Mobile Accordion:** On mobile, earnings and deductions sections are collapsible/expandable via Alpine.js `x-show` with smooth height transitions.

## 4. Notification System Architecture
- **Decision:** Create a `notifications` table with polymorphic `notifiable` relationship to store per-employee notifications with type, message, read status, and related entity reference.
- **Rationale:** A database-backed notification system allows consistent read/unread tracking across devices. Laravel's built-in notification system could be used, but a simpler custom table is lighter for this UI-focused requirement.
- **Schema:**
  ```
  notifications: id, employee_id, type (leave_approval, task_assigned, announcement), 
                 title, message, read_at, related_type, related_id, created_at
  ```
- **Alternatives Considered:** Laravel's built-in `DatabaseNotification` (viable but bundles unnecessary polymorphic complexity), localStorage-only tracking (rejected — doesn't sync across devices), real-time WebSockets (deferred — server-sent events or polling on page load is sufficient for this phase).
- **Badge Count:** `Notification::where('employee_id', $id)->whereNull('read_at')->count()` passed to layout via view composer. Updated on page load (no real-time polling in this phase).

## 5. Empty State Design
- **Decision:** Create a reusable `<x-empty-state>` Blade component with branded illustrations, a message, and an optional CTA button.
- **Rationale:** Empty states (no payslips for new hires, no notifications, no tasks) must feel intentional and helpful, not broken. Branded empty states reinforce the premium aesthetic.
- **Implementation:** Component accepts `icon`, `title`, `message`, and optional `action-url`/`action-label` props.

## 6. Localization & RTL
- **Decision:** All new text strings added to `en.json` and `ar.json`. Payslip receipt layout uses CSS logical properties and `direction`-aware text alignment.
- **Rationale:** Constitution Principle IV. Payslip number formatting respects locale (Arabic numerals optional). Currency symbols positioned correctly for RTL.
