---
description: "Task list template for feature implementation of Reminder Phrases"
---

# Tasks: Reminder Phrases

**Input**: Design documents from `/specs/004-reminder-phrases/`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Database schema initialization.

- [x] T001 Create migration for `reminder_phrases` table in `database/migrations/xxxx_xx_xx_xxxxxx_create_reminder_phrases_table.php`
- [x] T002 [P] Create `ReminderPhraseFactory` logic in `database/factories/ReminderPhraseFactory.php`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core model infrastructure and event enums that MUST be complete before ANY user story can be implemented.

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T003 [P] Create `NotificationEvent` Enum in `app/Enums/NotificationEvent.php` with `availableVariables()` mapping logic
- [x] T004 [P] Create `ReminderPhrase` Eloquent model in `app/Models/ReminderPhrase.php`
- [x] T005 Implement string substitution logic within `ReminderPhraseService` at `app/Services/ReminderPhraseService.php`

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Reminder Template Management (Priority: P1) 🎯 MVP

**Goal**: Admins can manage reminder phrases through an interface displaying an automated cheat sheet of variables.

**Independent Test**: Can be tested securely from the Admin panel by registering a new layout string and seeing the variables helper successfully render.

### Tests for User Story 1 (TDD Priority)

- [x] T006 [P] [US1] Create Feature test verifying CRUD protections and capabilities in `tests/Feature/Admin/ReminderPhraseTest.php`

### Implementation for User Story 1

- [x] T007 [US1] Implement `ReminderPhraseController` in `app/Http/Controllers/Admin/ReminderPhraseController.php`
- [x] T008 [US1] Register resource routes mapping to the controller in `routes/admin.php`
- [x] T009 [US1] Create Blade index grid view at `resources/views/admin/reminder_phrases/index.blade.php`
- [x] T010 [US1] Create Blade form implementation with Alpine JS variable UI rendering at `resources/views/admin/reminder_phrases/form.blade.php`

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently in the browser.

---

## Phase 4: User Story 2 - Automated Delivery of Reminders (Priority: P2)

**Goal**: System properly evaluates dashboard alerts against the phrase service, gracefully prioritizing DB strings before failing backward to language locales.

**Independent Test**: Simulating a subscription threshold hits the database caching and replaces raw `__('messages')` effectively.

### Tests for User Story 2 (TDD Priority)

- [x] T011 [P] [US2] Create integration test verifying dashboard phrase resolution in `tests/Feature/Client/DashboardReminderTest.php`

### Implementation for User Story 2

- [x] T012 [US2] Refactor string invocations to map to `ReminderPhraseService` within `resources/views/client/dashboard.blade.php`

**Checkpoint**: Both User Stories 1 AND 2 are functional independently.

---

## Phase 5: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect the master output.

- [x] T013 [P] Verify and finalize AR localization translation rendering in `lang/ar/messages.php` referencing the admin UI
- [x] T014 Execute full PHPUnit test harness guaranteeing 100% resolution validation across all `php artisan test` logic

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Phase 1 completion
- **User Stories (Phase 3+)**: Setup + Foundation must complete simultaneously to avoid class `does not exist` warnings.
- **Polish (Final Phase)**: Dependent on US2.

### Within Each User Story

- Test execution MUST precede domain routing logic. 
- Controllers precede blades in iteration order.

## Implementation Strategy

### MVP Delivery

1. Execute Phase 1 and 2 sequentially to construct the strict Database & Object topology.
2. Advance to User Story 1 to supply Administration features enabling text substitution.
3. Advance to User Story 2 bridging the Service substitution cleanly to the end-client viewport.
