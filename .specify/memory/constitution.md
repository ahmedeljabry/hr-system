<!--
  ╔══════════════════════════════════════════════════╗
  ║           SYNC IMPACT REPORT                     ║
  ╠══════════════════════════════════════════════════╣
  ║ Version change: 1.0.0 → 1.1.0                   ║
  ║ Bump rationale: MINOR — added bilingual Arabic  ║
  ║                 and English support             ║
  ║                                                  ║
  ║ Modified principles:                             ║
  ║   - Principle V (Bilingual RTL/LTR Localization) ║
  ║                                                  ║
  ║ Added sections:                                  ║
  ║   + Core Principles (7 principles)               ║
  ║   + Technology Stack (non-negotiable)             ║
  ║   + Security Requirements (non-negotiable)       ║
  ║   + Development Workflow                         ║
  ║   + Governance                                   ║
  ║                                                  ║
  ║ Removed sections: (none)                         ║
  ║                                                  ║
  ║ Templates requiring updates:                     ║
  ║   ✅ plan-template.md — Constitution Check       ║
  ║      section aligns with principles              ║
  ║   ✅ spec-template.md — no updates needed        ║
  ║   ✅ tasks-template.md — TDD gate aligns with    ║
  ║      Principle III                               ║
  ║                                                  ║
  ║ Follow-up TODOs: none                            ║
  ╚══════════════════════════════════════════════════╝
-->

# HR Management System — Constitution

## Core Principles

### I. Multi-Tenant Isolation (NON-NEGOTIABLE)

Every database query MUST be scoped to `client_id`. No controller,
service, or repository method may return data belonging to another
tenant. Cross-tenant data exposure is a critical security violation.

- All Eloquent models that belong to a tenant MUST use a global scope
  or trait that automatically filters by `client_id`.
- Direct SQL queries are forbidden (see Principle VI).
- Integration tests MUST include cross-tenant isolation assertions:
  attempting to access another client's data MUST return HTTP 403.

**Rationale**: The system manages sensitive HR data (salaries, national
IDs, contracts) for multiple companies. A single leak crosses legal
and contractual boundaries.

### II. Role-Guarded Access Control

Every route and controller method MUST be protected by role-based
middleware. The three roles are: `super_admin`, `client`, `employee`.

- Route groups MUST be wrapped in `auth` + `role:<allowed_role>`
  middleware.
- Controller methods MUST NOT contain inline role checks — delegate to
  middleware or policy classes.
- Employees MUST NOT self-register; they are created exclusively by
  their parent client.

**Rationale**: Unauthorized access to payroll, leave, or employee data
by the wrong role is a business-critical failure.

### III. Test-Driven Development (NON-NEGOTIABLE)

All business logic MUST be covered by tests written **before**
implementation. The Red-Green-Refactor cycle is strictly enforced.

- Feature tests MUST be written using PHPUnit.
- Each phase MUST include feature tests as defined in Plan.md tasks.
- No feature branch may be merged without a passing test suite
  (`php artisan test`).

**Rationale**: A $150-budget, 10-day timeline leaves zero room for
regression bugs. Tests are the safety net.

### IV. Service-Layer Architecture

All business logic MUST live in Service classes. Controllers are thin
dispatchers. Blade views contain zero logic.

- Controllers MUST only: validate input, call a service, return a
  response.
- Service classes MUST be the single source of truth for business
  rules (payroll calculations, leave balance, subscription checks).
- Repository pattern MUST be used for database access.
- No logic in Blade views — data comes pre-formatted from controllers
  or view-models.

**Rationale**: Keeps the codebase testable, maintainable, and
prevents spaghetti code under tight deadlines.

### V. Bilingual RTL/LTR Localization

The system MUST support both Arabic (RTL) and English (LTR), with Arabic
as the default.

- All user-facing strings MUST use Laravel's localization utility (`__('...')`).
- Blade views MUST dynamically set `dir` and `lang` based on current locale.
- CSS MUST use logical properties (`margin-inline-start`, `padding-inline-end`)
  to ensure layout flows correctly in both RTL and LTR.
- The UI MUST include a visible language switcher to toggle between 'ar' and 'en'.

**Rationale**: The business requirement has been updated to include English
support for broader accessibility, while maintaining Arabic as the primary focus.

### VI. Eloquent-Only Data Access (NON-NEGOTIABLE)

No raw SQL is permitted anywhere in the codebase. All database
operations MUST go through Eloquent ORM.

- `DB::raw()`, `DB::select()`, and `DB::statement()` are forbidden.
- Complex queries MUST use Eloquent query builder or scopes.
- Migrations MUST use the Schema builder, not raw DDL.

**Rationale**: Raw SQL bypasses tenant scoping, CSRF protection, and
makes the codebase harder to audit for security.

### VII. Private File Storage (NON-NEGOTIABLE)

All uploaded files (national ID images, contract documents) MUST be
stored on a private disk, never in the public directory.

- File uploads MUST be validated for type and size before storage.
- Access MUST be served via signed URLs or authenticated controller
  routes.
- The `storage/app/private/` directory is the only valid upload
  destination.

**Rationale**: National ID images and employment contracts are legally
protected personal data.

## Technology Stack (Non-Negotiable)

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 11 (PHP 8.3) |
| **Frontend** | Blade + Alpine.js |
| **Database** | MySQL 8 (`utf8mb4` charset) |
| **Auth** | Laravel Sanctum + role-based access |
| **Excel** | Maatwebsite/Laravel-Excel |
| **Testing** | PHPUnit (feature tests) |
| **Code Style** | PSR-12 |

- No alternative frameworks, ORMs, or testing libraries may be
  introduced without a constitution amendment.
- Alpine.js is permitted for interactive UI components within Blade
  views. A full SPA framework (React/Vue) is NOT permitted unless
  an amendment is ratified.

## Development Workflow

### Phased Delivery

The project is divided into 8 phases (see Plan.md). Each phase
follows the SDD loop:

1. `/speckit-specify` → Define WHAT the phase builds
2. `/speckit-plan` → Define HOW to build it
3. `/speckit-tasks` → Break into atomic, dependency-ordered tasks
4. `/speckit-implement` → Execute tasks (TDD: test first)
5. Review → update spec if needed → next phase

### Branch Strategy

Each phase MUST be developed on its own feature branch:
`feature/phase-N-<description>`. No direct commits to `main`.

### Code Review Gates

- Every task MUST be committed individually or in logical groups.
- Feature tests MUST pass before moving to the next phase.
- Cross-tenant isolation MUST be verified at the end of every phase
  that touches tenant-scoped data.

## Governance

This constitution supersedes all other development practices,
conventions, or ad-hoc decisions made during implementation.

- **Amendment process**: Any change to this constitution MUST be
  documented with a version bump, rationale, and migration plan for
  affected code.
- **Versioning**: Follows semantic versioning (MAJOR.MINOR.PATCH).
  - MAJOR: Principle removal or backward-incompatible redefinition.
  - MINOR: New principle or materially expanded guidance.
  - PATCH: Clarifications, wording, or typo fixes.
- **Compliance review**: All code produced by `/speckit-implement`
  MUST be verified against this constitution before phase sign-off.
- **Conflict resolution**: If a task conflicts with a principle,
  the principle wins. Escalate to the constitution for amendment if
  the principle is genuinely wrong.

**Version**: 1.1.0 | **Ratified**: 2026-04-04 | **Last Amended**: 2026-04-04
