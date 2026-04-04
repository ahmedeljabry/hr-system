# Implementation Plan: Reminder Phrases

**Branch**: `004-reminder-phrases` | **Date**: 2026-04-05 | **Spec**: [specs/004-reminder-phrases/spec.md](spec.md)
**Input**: Feature specification from `/specs/004-reminder-phrases/spec.md`

## Summary

The Reminder Phrases feature introduces a centralized database-driven templates engine overriding standard language files. This enables Super Admins to dynamically adjust phrasing for triggered notifications natively within the Laravel single-page App, supporting bidirectional Arabic/English localization and strict payload evaluation.

## Technical Context

**Language/Version**: PHP 8.3 / Laravel 11
**Primary Dependencies**: Eloquent, Tailwind CSS, Blade/Alpine.js
**Storage**: MySQL 8 (Production), SQLite (Testing)
**Testing**: PHPUnit / Laravel Feature Tests
**Target Platform**: Web (Multi-tenant B2B)
**Project Type**: Full-stack module extension
**Performance Goals**: <50ms rendering overhead per active phrase payload injection
**Constraints**: Super Admins only (No Tenant-level edits), In-app UI alerting only.
**Scale/Scope**: System-critical read-heavy module (~5 concurrent models at scale)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **I. Strict Multi-Tenant Isolation**: N/A (Feature operates globally across all tenants as a master configurations list per FR-005). Does not violate read integrity. ✅
- **II. TDD-First**: All ReminderPhrase generation events and fallback contingencies will be evaluated via isolation unit testing. ✅
- **III. Thin Controllers, Fat Services**: `ReminderPhraseService` handles payload interpolation logic (no domain evaluation in Http layer). ✅
- **IV. Bilingual UI First**: Implemented natively (`text_en` and `text_ar`). ✅
- **V. Eloquent Database Interactions**: Strictly adhered to via `ReminderPhrase::class`. ✅

## Project Structure

### Documentation (this feature)

```text
specs/004-reminder-phrases/
├── plan.md              
├── research.md          
├── data-model.md        
└── tasks.md             
```

### Source Code (repository root)

```text
app/
├── Enums/
│   └── NotificationEvent.php
├── Models/
│   └── ReminderPhrase.php
├── Services/
│   └── ReminderPhraseService.php
└── Http/
    └── Controllers/
        └── Admin/
            └── ReminderPhraseController.php

database/
└── migrations/
    └── xxxx_xx_xx_xxxxxx_create_reminder_phrases_table.php

resources/
└── views/
    └── admin/
        └── reminder_phrases/
            └── index.blade.php
            └── form.blade.php

tests/
├── Feature/
│   └── Admin/
│       └── ReminderPhraseTest.php
```

**Structure Decision**: Integrated tightly to the single-project backend MVC domain mapped to `/app` relying gracefully on strict namespace groupings (`Admin\`).

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| None | N/A | N/A |
