# Multi-tenant HR Management System Constitution

<!--
Sync Impact Report: Initialized
Version change: 0.0.0 → 1.0.0
Modified principles: 
  - [PRINCIPLE_1_NAME] → I. Strict Multi-Tenant Isolation
  - [PRINCIPLE_2_NAME] → II. TDD-First
  - [PRINCIPLE_3_NAME] → III. Thin Controllers, Fat Services
  - [PRINCIPLE_4_NAME] → IV. Bilingual UI First
  - [PRINCIPLE_5_NAME] → V. Eloquent Database Interactions
Added sections: None
Removed sections: None
Templates requiring updates: ✅ plan-template.md verified, ✅ spec-template.md verified, ✅ tasks-template.md verified
-->

## Core Principles

### I. Strict Multi-Tenant Isolation
All business logic, database queries, and route interactions MUST definitively scope data to the current tenant via `client_id` (or the equivalent authenticated relationship). Under no circumstances should cross-tenant data access be possible.

### II. TDD-First (Test-Driven Development)
All new features, models, services, and endpoints MUST be accompanied by comprehensive tests before or in tandem with implementation. The Red-Green-Refactor cycle is mandatory. Code coverage should ensure 100% test passing ratios.

### III. Thin Controllers, Fat Services
Controllers MUST ONLY handle HTTP request validations, authorization checks, and returning views or JSON responses. All complex business rules, mathematical computations (e.g., payroll aggregation), and state mutations MUST exist in distinct dedicated Service layer classes.

### IV. Bilingual UI First
All visible text strings in views MUST use localization keys (`__('messages.key')`) from the very beginning. The user interface MUST support both Arabic (RTL) and English (LTR) layouts cleanly out-of-the-box, leveraging Tailwind CSS conditional styling if necessary.

### V. Eloquent Database Interactions
Database operations MUST use the Laravel Eloquent ORM. Raw SQL is strictly forbidden to ensure cross-database compatibility (SQLite testing, MySQL production) and native security.

## Additional Constraints

The technology stack is strict: Laravel 11 (PHP 8.3), MySQL 8 (production), SQLite (in-memory tests), Tailwind CSS, Alpine.js, and Blade templates. No superfluous packages should be added unless they are heavily justified (e.g., PDF generation).

## Development Workflow

1. Design specifications (via `spec.md` and `plan.md`) must be reviewed and approved prior to generation of `tasks.md`.
2. Tasks must follow a strict modular delivery sequence, prioritizing setup migrations, followed by foundational models, then specific isolated User Stories.
3. Every test branch must explicitly verify multi-tenant isolation.
4. Any failure in automated tests (`php artisan test`) during development immediately halts progress; it MUST be triaged and resolved before proceeding to subsequent tasks. 

## Governance

This Constitution supersedes all ad-hoc architecture guidelines or practices. Any deviations require an amendment to this document alongside a proper migration or refactoring plan.
All pull requests must undergo automated review to verify alignment with these core principles (specifically verifying tenant isolation).

**Version**: 1.0.0 | **Ratified**: 2026-04-05 | **Last Amended**: 2026-04-05
