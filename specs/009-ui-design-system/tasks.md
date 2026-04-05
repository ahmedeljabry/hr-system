---
description: "Actionable, LLM-friendly task list for UI Design System foundational setup."
---

# Tasks: 009-ui-design-system

**Input**: Design documents from `/specs/009-ui-design-system/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Organization**: Tasks are grouped by user story to enable independent implementation. All descriptions explicitly denote file paths and precise actions.

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Core application setup for new fonts and styles.

- [x] T001 Register `Outfit` and `Inter` Google Fonts within the `<head>` of `resources/views/layouts/app.blade.php`.
- [x] T002 [P] Extend the Tailwind configuration (`tailwind.config.js`) to append `sans` to feature the new `Outfit` and `Inter` font families in `theme.extend.fontFamily`.
- [x] T003 Extend the Tailwind configuration (`tailwind.config.js`) to consume standard UI colors (`primary`, `surface`, `text-main`) linked to standard CSS variables in `theme.extend.colors`.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Preparing the CSS variable foundation before component styling.

- [x] T004 Configure the dark-mode ready CSS variables (Base HSL tokens for `--primary`, `--surface`, `--text-main`) in the `@layer base` block of `resources/css/app.css`.

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel.

---

## Phase 3: User Story 1 - Consistent Brand Visuals (Priority: P1) 🎯 MVP

**Goal**: Establish base colors, typography, and foundational elements overriding default tailwind.

**Independent Test**: Navigate to any guest view and verify the typography has switched to the new Google fonts and the color gradients match the new HSL values.

### Tests for User Story 1
- [x] T005 [P] [US1] Add a basic rendering test in `tests/Feature/LayoutTest.php` ensuring `app.blade.php` successfully compiles the newly added `<head>` font links with status 200.

### Implementation for User Story 1
- [x] T006 [P] [US1] Update `resources/views/layouts/app.blade.php` to actively cast the application's root body text color to the new `text-text-main` tailwind string.
- [x] T007 [P] [US1] Update `resources/views/layouts/app.blade.php` to cast the main layout background to the new `bg-surface` color structure.

**Checkpoint**: At this point, the global layout visually respects the new tailored brand identity.

---

## Phase 4: User Story 2 - UI Component Reusability & Animation (Priority: P2)

**Goal**: Create independent, highly standardized anonymous Blade components (Buttons, Inputs, Cards).

**Independent Test**: Mount the components in a test blade file and hover over them to view the <300ms transition.

### Tests for User Story 2 
- [x] T008 [P] [US2] Create Blade component rendering tests in `tests/Feature/UIComponentsTest.php` ensuring `<x-button>`, `<x-input>`, and `<x-card>` return correctly compiled HTML strings.

### Implementation for User Story 2
- [x] T009 [P] [US2] Create `resources/views/components/card.blade.php` wrapped in a div featuring `bg-white dark:bg-gray-800 shadow-xl rounded-2xl` and accepting a `$slot`.
- [x] T010 [P] [US2] Create `resources/views/components/button.blade.php` featuring `transition-all duration-300 hover:shadow-lg` and specific background color classes.
- [x] T011 [P] [US2] Create `resources/views/components/input.blade.php` encompassing an `<input>` tag with predefined borders, rings, and focus styles, including a smooth standard `transition-colors duration-200`.

**Checkpoint**: Standard atomic components are now available globally across Blade views.

---

## Phase 5: User Story 3 - Native RTL/LTR Logical Properties (Priority: P2)

**Goal**: Utilize Tailwind’s CSS logical properties.

**Independent Test**: Toggle the application’s language parameter (`ar` to `en`) and observe zero UI padding bleeds.

### Tests for User Story 3
- [x] T012 [P] [US3] Ensure `tests/Feature/UIComponentsTest.php` strictly passes layout assertions that `<x-button>` and `<x-input>` contain `-inline` logical tailwind property strings (e.g., `ps-4`, `pe-4`).

### Implementation for User Story 3
- [x] T013 [P] [US3] Refactor the internal HTML of `resources/views/components/button.blade.php` to replace any absolute paddings (like `pl-4` / `pr-4`) with logical equivalent paddings (`ps-4`, `pe-4`).
- [x] T014 [US3] Refactor `resources/views/components/input.blade.php` mirroring the absolute constraints above directly to `ps-` and `pe-` or `ms-` / `me-`.

**Checkpoint**: All user stories should now be independently functional.

---

## Phase N: Polish & Cross-Cutting Concerns

**Purpose**: Cleanup, refactoring, and integration of the components.

- [x] T015 Run `npm run build` to confirm all CSS properties compile via Vite successfully.
- [x] T016 Implement the newly standardized `<x-button>` within `resources/views/auth/login.blade.php` to replace the existing submit button.
- [x] T017 Implement the newly standardized `<x-input>` within `resources/views/auth/login.blade.php` for the Email and Password fields.

---

## Dependencies & Execution Order

### Phase Dependencies
- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### Parallel Opportunities
- Foundational `app.css` styling and `tailwind.config.js` edits can be done concurrently by modifying distinct files.
- All User Story 2 components (`x-button`, `x-input`, `x-card`) can be implemented simultaneously.

## Implementation Strategy

1. Execute Phase 1 and 2 to lay the config/CSS foundation.
2. Knock out US1 (global body styling + fonts).
3. Systematically create the Blade Components in parallel (US2).
4. Sift through each Blade Component to enforce logical padding properties (US3).
5. Compile via `npm` and insert components into the live Login page to demonstrate value.
