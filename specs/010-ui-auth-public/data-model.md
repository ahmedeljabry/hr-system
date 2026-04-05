# Data Model & Components: Authentication & Public Views

Since this feature is purely a presentation layer overhaul of existing auth views, no database changes are required. This document outlines the **Blade Component schemas and UI contracts**.

## New Blade Components

### 1. `x-auth-split-layout`
A shared wrapper for all auth pages implementing the split-screen pattern.
* **Path:** `resources/views/components/auth-split-layout.blade.php`
* **Props:**
  * `title` (string) — Page heading shown in the form panel
  * `subtitle` (string, optional) — Subtext below the title
* **Slots:**
  * `$slot` — The auth form content (right/inline-end panel)
  * `$branding` (named slot, optional) — Custom branding content for the imagery panel. If omitted, uses default brand imagery.
* **Behavior:** Desktop: two-column CSS Grid. Mobile (< 768px): single-column, branding panel hidden.

### 2. `x-validation-feedback`
An inline validation feedback component with smooth CSS transitions.
* **Path:** `resources/views/components/validation-feedback.blade.php`
* **Props:**
  * `field` (string) — The form field name to check `$errors->has()`
  * `success-message` (string, optional) — Message shown when validation passes.
* **Behavior:** Shows error messages with fade-in slide-down transition (200ms). Supports real-time client-side feedback via Alpine.js `x-show`.

### 3. `x-step-indicator`
A visual progress indicator for multi-step forms (onboarding).
* **Path:** `resources/views/components/step-indicator.blade.php`
* **Props:**
  * `current` (int) — Current step number (1-indexed)
  * `total` (int) — Total number of steps
  * `labels` (array, optional) — Step label strings

## Error Page Templates

### 404, 403, 500 Pages
* **Paths:** `resources/views/errors/{404,403,500}.blade.php`
* **Structure:**
  * Centered layout using design system tokens
  * Inline SVG illustration (themed with CSS custom properties)
  * Error code heading + friendly message
  * CTA button: "Return to Home" (unauthenticated) or "Return to Dashboard" (authenticated)
  * All text via localization keys: `__('errors.404_title')`, etc.

## SVG Illustration Assets

| File | Error | Visual Concept |
|------|-------|----------------|
| `errors/404.svg` | Not Found | Broken link / lost path line-art |
| `errors/403.svg` | Forbidden | Lock / shield line-art |
| `errors/500.svg` | Server Error | Cloud with lightning bolt line-art |

All SVGs use `currentColor` or CSS custom properties for dynamic theming.
