# Data Model & Components: Client Portal UI Refinement

This phase introduces no new database tables. All data is sourced from existing Employee, Payroll, and File models. This document outlines new Blade components.

## New Blade Components

### 1. `x-view-toggle`
A toggle button group for switching between grid and list views.
* **Path:** `resources/views/components/view-toggle.blade.php`
* **Props:**
  * `default` (string) — Default view mode: `grid` or `list`. Default: `grid`
  * `storage-key` (string, optional) — localStorage key for persistence. Default: `view_mode`
* **Behavior:** Two icon buttons (grid icon / list icon). Active state highlighted with primary color. Alpine stores current mode and emits `view-changed` event.

### 2. `x-avatar`
An employee avatar with automatic initials fallback.
* **Path:** `resources/views/components/avatar.blade.php`
* **Props:**
  * `name` (string) — Employee full name (used for initials and color generation)
  * `image` (string, optional) — Avatar image URL
  * `size` (string) — `sm` (32px), `md` (48px), `lg` (64px). Default: `md`
* **Behavior:** Shows image if available. Otherwise renders first+last initials on a deterministic HSL background.

### 3. `x-slide-over`
A reusable side panel overlay for contextual forms.
* **Path:** `resources/views/components/slide-over.blade.php`
* **Props:**
  * `title` (string) — Panel header title
  * `width` (string) — Panel width: `md` (400px), `lg` (600px), `xl` (800px). Default: `lg`
  * `confirm-close` (bool) — Enable unsaved changes confirmation. Default: `false`
* **Slots:**
  * `$slot` — Panel body content
  * `$footer` (named slot, optional) — Footer with action buttons
* **Behavior:**
  * Opens from inline-end with `translateX` transition (300ms)
  * Semi-transparent backdrop (click to close unless `confirm-close`)
  * Escape key closes panel
  * Traps focus within panel (accessibility)
  * On mobile (< 768px): full-screen overlay instead of side panel

### 4. `x-drop-zone`
A drag-and-drop file upload area with progress tracking.
* **Path:** `resources/views/components/drop-zone.blade.php`
* **Props:**
  * `action` (string) — Upload endpoint URL
  * `accept` (string, optional) — Accepted MIME types. Default: `*/*`
  * `max-size` (int, optional) — Max file size in MB. Default: `10`
  * `name` (string) — Form field name. Default: `file`
* **Behavior:**
  * Default state: dashed border with upload icon and text
  * Drag hover: border highlights with primary color, background tints
  * Uploading: progress bar fills with percentage text
  * Complete: checkmark animation, file added to list
  * Error: red border with error message

### 5. `x-upload-progress`
A file upload progress bar component.
* **Path:** `resources/views/components/upload-progress.blade.php`
* **Props:**
  * `filename` (string) — Name of the file being uploaded
  * `progress` (int) — Upload percentage (0-100)
  * `status` (string) — `uploading`, `complete`, `error`

## Employee Grid Card Layout

```
┌──────────────────────┐
│     ┌──────────┐     │
│     │  Avatar  │     │
│     │  / Init  │     │
│     └──────────┘     │
│    Employee Name     │
│    Department        │
│  ┌────────────────┐  │
│  │ Status Badge   │  │
│  └────────────────┘  │
│  [View] [Edit] [...]│
└──────────────────────┘
```

Card uses Phase 009 design tokens (glassmorphism card, subtle hover lift, branded status badge colors).
