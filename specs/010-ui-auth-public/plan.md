# Implementation Plan: 010-ui-auth-public

**Branch**: `010-ui-auth-public` | **Date**: 2026-04-05 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/010-ui-auth-public/spec.md`

## Summary
Overhaul all authentication and public-facing views (Login, Registration, Password Reset, Error Pages) with a premium split-screen layout, smooth validation feedback, custom SVG error illustrations, and full RTL/LTR bilingual support. Mobile devices hide the imagery panel and center the auth form.

## Technical Context

**Language/Version**: PHP 8.3 & HTML/Blade  
**Primary Dependencies**: TailwindCSS, Alpine.js, Design System tokens from Phase 009  
**Storage**: N/A (Presentation layer only — backend auth endpoints pre-exist)  
**Testing**: PHPUnit / Laravel Browser Kit (view rendering, form validation display)  
**Target Platform**: Web Browsers (Desktop & Mobile)  
**Project Type**: Laravel Monolith (Blade Views)  
**Performance Goals**: Auth pages load < 1s, validation feedback < 100ms, animations < 300ms  
**Constraints**: CSS Logical Properties for RTL/LTR, mobile-first responsive, WCAG 2.1 AA  
**Scale/Scope**: 6 views (Login, Register, Password Reset, 404, 403, 500)

## Constitution Check

*GATE: Passed*

- **I. Strict Multi-Tenant Isolation**: N/A for auth/public views (pre-authentication context).
- **II. TDD-First**: View rendering tests will verify split-screen layout, component usage, and error page responses.
- **III. Thin Controllers, Fat Services**: N/A — no controller logic changes; this is purely template/view work.
- **IV. Bilingual UI First**: All text uses localization keys. CSS logical properties ensure RTL/LTR layout symmetry.
- **V. Eloquent Database Interactions**: N/A for presentation layer.

## Project Structure

### Documentation (this feature)

```text
specs/010-ui-auth-public/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
└── tasks.md             # Phase 2 output (via /speckit.tasks)
```

### Source Code (repository root)

```text
resources/
├── views/
│   ├── auth/
│   │   ├── login.blade.php          # Split-screen login redesign
│   │   ├── register.blade.php       # Split-screen registration redesign
│   │   └── passwords/
│   │       └── reset.blade.php      # Password reset redesign
│   ├── errors/
│   │   ├── 404.blade.php            # Custom branded 404 with SVG illustration
│   │   ├── 403.blade.php            # Custom branded 403 with SVG illustration
│   │   └── 500.blade.php            # Custom branded 500 with SVG illustration
│   ├── layouts/
│   │   └── auth.blade.php           # New split-screen auth layout (shared)
│   └── components/
│       ├── auth-split-layout.blade.php   # Split-screen wrapper component
│       └── validation-feedback.blade.php # Smooth inline validation component
├── css/
│   └── app.css                      # Auth-specific token extensions
└── images/
    └── errors/
        ├── 404.svg                  # Custom line-art SVG illustration
        ├── 403.svg                  # Custom line-art SVG illustration
        └── 500.svg                  # Custom line-art SVG illustration
```

**Structure Decision**: Using Laravel's standard Blade view directories. A shared `auth.blade.php` layout wraps the split-screen pattern. Custom SVG illustrations stored in `resources/images/errors/` or `public/images/errors/`.

## Complexity Tracking

*No constitution violations present. Standard presentation layer pattern followed.*
