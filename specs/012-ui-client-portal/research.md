# Technical Research & Decisions: Client Portal UI Refinement

## Overview
This document consolidates the technical research and architecture choices for implementing the Client Portal UI refinement (Phase 012).

## 1. Grid/List View Toggle Architecture
- **Decision:** Use Alpine.js reactive state to toggle between grid and list view modes. Both view modes are rendered as separate Blade partials (`_grid-card.blade.php` and `_list-row.blade.php`), conditionally shown via `x-show`.
- **Rationale:** Rendering both partials and toggling visibility with Alpine provides instant (<200ms) switching without AJAX requests. For typical employee lists (<100 employees per tenant), the DOM overhead is negligible.
- **Alternatives Considered:** AJAX-fetched partial replacement (rejected — adds latency for a simple toggle), Livewire component (rejected — Alpine is sufficient for client-side state), URL-based toggle (rejected — causes page reload).
- **State Persistence:** View preference stored in `localStorage.employee_view_mode` to persist across page navigations.

## 2. Employee Avatar Component
- **Decision:** Create an `<x-avatar>` Blade component that accepts a `name` and optional `image` prop. When no image is available, display the employee's initials on a color-coded background.
- **Rationale:** Many employees may not have uploaded photos. The initials fallback ensures a consistent, visually appealing grid without broken image icons.
- **Color Generation:** Background color derived from a hash of the employee's name, selecting from the HSL palette established in Phase 009. This provides consistent, deterministic colors per employee.

## 3. Slide-Over Panel Architecture
- **Decision:** Create a reusable `<x-slide-over>` Blade component using Alpine.js for open/close state and CSS transitions for animation (transform: translateX).
- **Rationale:** Slide-over panels are a modern UX pattern. Using Alpine.js `x-transition` provides smooth 300ms slide and fade animations. The panel overlays the current page with a semi-transparent backdrop.
- **Alternatives Considered:** Full modal dialogs (rejected — modals obscure context more than slide-overs), inline expanding rows (rejected — doesn't work for complex forms like payroll).
- **Forms:** Existing form Blade partials are included inside the slide-over via `@include`. Form submission uses standard HTML POST (no AJAX needed — the form will redirect on success).
- **Unsaved Changes:** Alpine tracks form dirty state via `x-data` comparing initial and current field values. On close attempt with dirty state, a confirmation dialog appears.

## 4. Drag-and-Drop File Upload
- **Decision:** Create a `<x-drop-zone>` Blade component using native HTML5 Drag and Drop API with Alpine.js event handlers. Files are uploaded via `fetch()` with `XMLHttpRequest` for progress tracking.
- **Rationale:** Native drag-and-drop API is well-supported across modern browsers. `XMLHttpRequest.upload.onprogress` provides real-time progress percentage, which drives a CSS-animated progress bar.
- **Alternatives Considered:** FilePond library (rejected — adds 40KB+ dependency for a single upload zone), Livewire file uploads (rejected — Alpine with native APIs is lighter and sufficient).
- **Progress Implementation:** Alpine `x-data` tracks `uploadProgress` (0-100). CSS `width` transition animates the progress bar smoothly. On completion, a success animation plays (checkmark icon fade-in).

## 5. Mobile Responsiveness
- **Decision:** Grid view defaults to single-column on mobile (< 640px), two-column on tablet (640px–1024px), three-column on desktop (> 1024px). Slide-over panels become full-screen overlays on mobile.
- **Rationale:** On mobile, there's insufficient width for a side panel. Full-screen overlay with a close button maintains usability. Grid cards stack naturally with CSS Grid responsive columns.
