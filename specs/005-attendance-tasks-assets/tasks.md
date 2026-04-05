# Tasks: Operations Management (Attendance, Tasks & Assets)

**Input**: Design documents from `/specs/005-attendance-tasks-assets/`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, quickstart.md

**Tests**: TDD is mandatory per Constitution Principle II. Feature tests will be created for each user story.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

- [x] T001 Create feature directory structure in `specs/005-attendance-tasks-assets/`
- [x] T002 [P] Update `lang/en.json` and `lang/ar.json` with base keys for Attendance, Tasks, and Assets
- [x] T003 [P] Register new routes in `routes/client.php` and `routes/employee.php` placeholders

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

- [x] T004 Create migration for `attendance` table in `database/migrations/`
- [x] T005 Create migration for `tasks` table in `database/migrations/`
- [x] T006 Create migration for `assets` table in `database/migrations/`
- [x] T007 [P] Create `Attendance` model with `BelongsToClient` scope in `app/Models/Attendance.php`
- [x] T008 [P] Create `Task` model with `BelongsToClient` scope in `app/Models/Task.php`
- [x] T009 [P] Create `Asset` model with `BelongsToClient` scope in `app/Models/Asset.php`
- [x] T010 [P] Create `AttendanceService` blueprint in `app/Services/AttendanceService.php`
- [x] T011 [P] Create `TaskService` blueprint in `app/Services/TaskService.php`
- [x] T012 [P] Create `AssetService` blueprint in `app/Services/AssetService.php`

**Checkpoint**: Foundation ready - user story implementation can now begin.

---

## Phase 3: User Story 1 - Employee Attendance Tracking (Priority: P1) 🎯 MVP

**Goal**: Clients record daily attendance statuses and notes for employees via a bulk-entry UI.

**Independent Test**: Login as Client, go to `/client/attendance`, select a date, set statuses for all employees, and verify DB records exist for that date/employee combination.

### Tests for User Story 1
- [x] T013 Create `AttendanceFactory` for testing.
- [x] T014 Implement `AttendanceService::getAttendanceForDate` and `AttendanceService::bulkUpdateAttendance`.
- [x] T015 Create `AttendanceController` and store action.
- [x] T016 Build bulk attendance entry view.
- [x] T017 Add "Attendance" link to Sidebar.

**Checkpoint**: User Story 1 is functional and verifiable.

---

## Phase 4: User Story 2 - Task Assignment & Viewing (Priority: P2)

**Goal**: Clients create and assign tasks; employees view their tasks as read-only.

**Independent Test**: Client creates a task for Employee A. Employee A logs in and sees the task. Employee A cannot see an 'edit' or 'update' button.

### Tests for User Story 2
- [x] T018 Create `TaskTest` for Client.
- [x] T019 Create `TaskTest` for Employee.

### Implementation for User Story 2
- [x] T020 Implement `TaskService::createTask` and `TaskService::getTasksForEmployee`.
- [x] T021 Create `TaskController` for Client.
- [x] T022 Create `TaskController` for Employee.
- [x] T023 Build Task CRUD views for Client.
- [x] T024 Build Read-Only Task list for Employee.

**Checkpoint**: User Story 2 is functional for both roles.

---

## Phase 5: User Story 3 - Asset Inventory Tracking (Priority: P3)

**Goal**: Clients track organizational assets (Serial Number based) assigned to employees.

**Independent Test**: Client assigns a "Laptop (SN: 12345)" to Employee B. Employee B views their asset list and sees the laptop uniquely identified.

### Tests for User Story 3
- [x] T025 Create `AssetTest` for Client.
- [x] T026 Create `AssetTest` for Employee.
- [x] T027 Implement `AssetService::assignAsset` and `AssetService::getAssetsForEmployee`.
- [x] T028 Create `AssetController` for Client.
- [x] T029 Create `AssetController` for Employee.
- [x] T030 Build Asset CRUD views for Client.
- [x] T031 Build Read-Only Asset list for Employee.

**Checkpoint**: User Story 3 is functional.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Final verification, localization, and multi-tenant checks.

- [x] T032 Verify 100% Arabic translation for all new UI strings.
- [x] T033 Verify RTL layout integrity.
- [x] T034 Run exhaustive `php artisan test` across all phases.
- [x] T035 Verify zero cross-tenant leakage for tasks/assets.

---

## Dependencies & Execution Order

### Phase Dependencies
- **Phase 1 & 2** are prerequisites for all User Stories.
- **US1 (Attendance)** is the MVP and should be completed first.
- **US2 & US3** are independent and can be implemented in parallel after Phase 2.

### Parallel Opportunities
- Migrations (T004-T006)
- Model Creation (T007-T009)
- Services (T010-T012)
- US2 and US3 Implementation (Phases 4 & 5)
- Arabic Localization (T032)
