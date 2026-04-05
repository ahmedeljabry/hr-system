# Technical Research & Decisions: Authentication & Public Views Polish

## Overview
This document consolidates the technical research and architecture choices for implementing the Authentication & Public Views overhaul (Phase 010). All decisions build upon the Design System established in Phase 009.

## 1. Split-Screen Auth Layout Architecture
- **Decision:** Create a shared `auth.blade.php` layout with a CSS Grid two-column split. The left panel holds branded imagery/value propositions; the right panel holds the auth form.
- **Rationale:** A shared layout prevents duplication across Login, Register, and Password Reset views. CSS Grid with `grid-template-columns: 1fr 1fr` at desktop, collapsing to `1fr` at mobile (hiding imagery via `display: none` on screens < 768px). This follows the clarification decision.
- **Alternatives Considered:** Flexbox split (rejected — CSS Grid provides more precise column control), separate layouts per view (rejected — excessive duplication).

## 2. Mobile Responsive Strategy
- **Decision:** On mobile viewports (< 768px), the imagery/branding panel is completely hidden via `@media` query. The auth form centers in a single-column layout.
- **Rationale:** Clarification session confirmed this approach. Reduces bandwidth on mobile (imagery assets not loaded), focuses user attention on the form.
- **Alternatives Considered:** Stacked layout with condensed banner (rejected by stakeholder), swipeable carousel (rejected — overcomplicated for auth context).

## 3. Inline Validation Feedback
- **Decision:** Use Alpine.js `x-on:input` handlers combined with a `<x-validation-feedback>` Blade component that provides smooth CSS-transitioned error/success states.
- **Rationale:** Laravel's server-side validation already exists. The enhancement is purely visual — adding smooth transitions (opacity + transform) when error messages appear/disappear, and real-time format validation before submission (email format, password strength indicator).
- **Alternatives Considered:** Livewire validation (rejected — Alpine is sufficient for simple visual feedback; Livewire adds unnecessary server round-trips for client-side UI enhancement).

## 4. Error Page SVG Illustrations
- **Decision:** Create custom, lightweight SVG line-art illustrations for each error type (404, 403, 500), themed with the Phase 009 HSL color tokens.
- **Rationale:** Clarification session confirmed custom SVG illustrations. Inline SVGs allow dynamic color theming via CSS custom properties, ensuring consistency with the design system. They scale perfectly across all viewports and are lightweight (< 5KB each).
- **Alternatives Considered:** Icon-based minimal design (rejected — less visual impact), stock photography (rejected — doesn't match branded aesthetic).

## 5. Onboarding Flow Enhancement
- **Decision:** Enhance the Client registration form with a visual step indicator (Step 1 of 3 style progress bar) using the existing multi-step form architecture. Each step gets a smooth CSS transition.
- **Rationale:** The onboarding process already exists functionally. This is a visual polish — adding step indicators, smooth transitions between stages, and consistent use of Phase 009 tokens.
- **Alternatives Considered:** Full wizard component rebuild (rejected — existing multi-step structure is adequate; only visual treatment needed).

## 6. Localization & RTL Handling
- **Decision:** All auth views use `__('auth.key')` localization keys (already in place for most strings). CSS logical properties ensure the split-screen layout mirrors correctly in RTL mode (imagery on inline-end, form on inline-start).
- **Rationale:** Constitution Principle IV mandates bilingual-first. Logical properties swap the grid column order automatically when `dir="rtl"` is set.
