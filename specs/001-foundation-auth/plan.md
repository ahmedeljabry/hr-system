# Implementation Plan: Foundation & Authentication

**Branch**: `001-foundation-auth` | **Date**: 2026-04-04 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `specs/001-foundation-auth/spec.md`

## Summary

Build the foundational authentication system for a multi-tenant HR
management application. This phase delivers: user registration with
automatic client/subscription creation, secure login/logout with
session management, role-based route protection for three roles
(super_admin, client, employee), subscription status enforcement,
and super admin seeding. All UI supports Bilingual (Arabic/English) 
with RTL/LTR directional support.

## Technical Context

**Language/Version**: PHP 8.3 / Laravel 11
**Primary Dependencies**: Laravel Sanctum (auth), Blade + Alpine.js (UI)
**Storage**: MySQL 8 (utf8mb4 charset)
**Testing**: PHPUnit (feature tests, TDD)
**Target Platform**: Linux web server (Apache/Nginx)
**Project Type**: Web application (multi-tenant SaaS)
**Performance Goals**: Standard web app (<2s page load)
**Constraints**: $150 budget, 10-day timeline, Bilingual Arabic/English
**Scale/Scope**: ~50 concurrent users, 3 roles, 8 screens in this phase

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| # | Principle | Status | Evidence |
|---|-----------|--------|----------|
| I | Multi-Tenant Isolation | ✅ PASS | `Client` model scoped by `client_id`; global scope trait planned |
| II | Role-Guarded Access | ✅ PASS | `RoleMiddleware` guards all route groups; no inline checks |
| III | Test-Driven Development | ✅ PASS | Feature tests defined for every user story |
| IV | Service-Layer Architecture | ✅ PASS | `AuthService`, `SubscriptionService` handle logic; thin controllers |
| V | Bilingual RTL/LTR | ✅ PASS | All views dynamically use `dir`/`lang`; translation strings used |
| VI | Eloquent-Only Data Access | ✅ PASS | No raw SQL; Schema builder for migrations |
| VII | Private File Storage | ⬜ N/A | No file uploads in Phase 1 |

**Gate result**: ✅ ALL PASS — proceed to Phase 0.

## Project Structure

### Documentation (this feature)

```text
specs/001-foundation-auth/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output (routes)
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
app/
├── Models/
│   ├── User.php
│   └── Client.php
├── Services/
│   ├── AuthService.php
│   └── SubscriptionService.php
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   └── LogoutController.php
│   │   ├── LanguageController.php
│   │   └── Admin/
│   │       └── ClientController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   ├── CheckSubscription.php
│   │   └── SetLocale.php
│   └── Requests/
│       ├── LoginRequest.php
│       └── RegisterRequest.php
├── Traits/
│   └── BelongsToTenant.php
└── Providers/

config/
├── auth.php
└── app.php

database/
├── migrations/
│   ├── xxxx_create_users_table.php
│   └── xxxx_create_clients_table.php
└── seeders/
    └── SuperAdminSeeder.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php           # RTL/LTR dynamic layout with Language Switcher
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── admin/
│   │   └── clients/
│   │       └── index.blade.php
│   ├── client/
│   │   └── dashboard.blade.php
│   ├── employee/
│   │   └── dashboard.blade.php
│   └── subscription/
│       └── renewal.blade.php
└── lang/
    ├── ar/
        ├── auth.php
        ├── validation.php
        └── messages.php
    └── en/
        ├── auth.php
        ├── validation.php
        └── messages.php

routes/
├── web.php
├── admin.php
├── client.php
└── employee.php

tests/
└── Feature/
    ├── Auth/
    │   ├── RegistrationTest.php
    │   ├── LoginTest.php
    │   ├── LogoutTest.php
    │   └── RoleMiddlewareTest.php
    ├── Admin/
    │   └── ClientManagementTest.php
    └── Subscription/
        └── SubscriptionCheckTest.php
```

**Structure Decision**: Standard Laravel 11 monolith. No separate
frontend — Blade + Alpine.js for all UI. Routes split into grouped
files by role area (`admin.php`, `client.php`, `employee.php`) loaded
in `web.php` with appropriate middleware stacks.

## Complexity Tracking

> No constitution violations — no complexity justification needed.
