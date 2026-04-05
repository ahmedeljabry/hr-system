---
description: "Actionable, LLM-friendly task list for Phase 012 Client Portal UI/UX refinement."
---

# Tasks: 012-ui-client-portal

**Input**: Design documents from `/specs/012-ui-client-portal/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Organization**: Tasks follow strict user story segregation to maintain isolation and incrementality.

## Phase 1: Setup

**Purpose**: Establish base components that phase features heavily rely on.

- [x] T001 Review `resources/views/client/employees/index.blade.php` to ensure existing Employee data mappings work smoothly before UI overhaul.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core UI component blocks needed by the primary directory and entry forms.

- [x] T002 Create the avatar generator script/helper. Add `getInitials()` and `getColorHex()` functions globally (via a PHP helper file or within the component class).
- [x] T003 Create `resources/views/components/avatar.blade.php` processing `$name` and optional `$image`. Ensure it uses the helper logic and HSL logic to provide the deterministic background.
- [x] T004 Create `resources/views/components/view-toggle.blade.php`. Set up an Alpine `x-data` element storing the active mode in localStorage and deploying `$dispatch('view-changed')` when toggled.

**Checkpoint**: Reusable components are primed to augment the directory interface.

---

## Phase 3: User Story 1 - Rich Employee Directory Views (Priority: P1) 🎯 MVP

**Goal**: Deliver a grid vs list display layout decoupled completely from page reloads.

**Independent Test**: Navigate to the client directory. Switch views. Confirm local-storage retention.

### Implementation for User Story 1

- [x] T005 [P] [US1] Create the specific card partial: `resources/views/client/employees/_grid-card.blade.php`. Utilize glassmorphism styles, `<x-avatar>`, and standard UI flex containers.
- [x] T006 [P] [US1] Create the specific list row partial: `resources/views/client/employees/_list-row.blade.php`. Map to standard table dimensions and tailwind layout strings.
- [x] T007 [US1] Overhaul `resources/views/client/employees/index.blade.php`. Encompass the directory loop with Alpine JS `x-data="{ viewMode: localStorage.getItem('view_mode') || 'grid' }"` catching the `@view-changed` event. Inside the data loop, yield both partials, hiding them dynamically `x-show="viewMode === 'grid'"`.

**Checkpoint**: The employee directory dynamically toggles with zero backend penalty.

---

## Phase 4: User Story 2 - Slide-Over Panel Forms (Priority: P2)

**Goal**: Transform standard full-page forms into contextual side-panels overlaying the directory.

**Independent Test**: On the directory view, attempt to interact with "Add Employee" or "Run Payroll." View the animation behaviors.

### Implementation for User Story 2

- [x] T008 [P] [US2] Create the overarching `resources/views/components/slide-over.blade.php` layout wrapper. Implement Alpine JS for transitioning its transform offsets (translateX) based on boolean trigger flags. Ensure `x-on:click.outside` exists unless the unsaved confirm prop is passed.
- [x] T009 [US2] Update `resources/views/client/employees/index.blade.php` to embed the `<x-slide-over>` component hidden by default. Embed the employee creation form inside the slide panel's slot.
- [x] T010 [US2] Refactor the "Add Employee" button to trigger the Alpine variable opening the slide panel instead of initiating an HTML page redirect.
- [x] T011 [US2] (Optional based on scope) Refactor `resources/views/client/payroll/run.blade.php` to be invokable via a slide-over modal similarly using `<x-slide-over>`.

**Checkpoint**: Forms now load dynamically, preserving structural context.

---

## Phase 5: User Story 3 - Enhanced Document Upload UX (Priority: P3)

**Goal**: Utilize HTML drag and drop for a seamless upload pipeline tied into progress states.

**Independent Test**: Add an employee document. Verify the responsive dashed line drop target and progressive animation bar.

### Implementation for User Story 3

- [x] T012 [P] [US3] Create `resources/views/components/upload-progress.blade.php` containing nested DIVs. Set the child div's width linked directly to Alpine's `uploadProgress` variable alongside standard CSS transition durations.
- [x] T013 [P] [US3] Create `resources/views/components/drop-zone.blade.php` establishing native event handlers (`x-on:dragover.prevent`, `x-on:drop.prevent`) to capture the files into an Alpine method.
- [x] T014 [US3] Update `resources/views/components/drop-zone.blade.php` inner Javascript logic to dispatch an `XMLHttpRequest` targeting the `$action` prop endpoints, binding `.upload.onprogress` data to animate `<x-upload-progress>`, followed by emitting an upload success event on response.
- [x] T015 [US3] Integrate `<x-drop-zone>` natively inside the Employee Profile view's files tab, providing it with the pre-existing file upload backend route.

**Checkpoint**: Client interactions for managing state changes via docs are extremely smooth.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Validating functionality.

- [x] T016 Check Arabic RTL direction bindings to ensure the slide panel transitions specifically map to `-translate-x-full` correctly matching the inline layout flow. Evaluate Grid card row wraps inside Arabic templates.
- [x] T017 Execute `npm run build` rendering Tailwind dependencies.

## Dependencies & Execution Order

- **Foundational Phase 2** components must be created natively before attacking specific user stories.
- **US1** and **US2** operate on identical directories. Implement US1 first so that US2's Slide Panel trigger attaches to the completed view.
- **US3** touches independent document endpoints and can be worked on completely asynchronously.
