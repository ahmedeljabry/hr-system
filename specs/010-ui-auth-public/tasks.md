---
description: "Actionable, LLM-friendly task list for Phase 010 Auth & Public Views UI/UX refinement."
---

# Tasks: 010-ui-auth-public

**Input**: Design documents from `/specs/010-ui-auth-public/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Organization**: Tasks are grouped by user story to enable independent implementation. All descriptions explicitly denote file paths and precise actions.

## Phase 1: Setup

**Purpose**: Initial project alignment for Phase 010.

- [x] T001 Verify Phase 009 Design System assets (app.css, Google Fonts) are present and TailwindCSS is correctly configured.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core layout components that all auth views depend on.

- [x] T002 Create the shared split-screen layout in `resources/views/layouts/auth.blade.php`. Include the head, basic styling, and two main placeholder column slots.
- [x] T003 Create the `x-auth-split-layout` component in `resources/views/components/auth-split-layout.blade.php`. Implement the CSS Grid logic (`grid-cols-1 md:grid-cols-2`) utilizing logical CSS properties to ensure the image panel hides on mobile and the form centers correctly.
- [x] T004 Create the `x-validation-feedback` component in `resources/views/components/validation-feedback.blade.php` with Alpine.js attributes for smooth fade-in and transform-slide-down transitions.

**Checkpoint**: Shared layout and foundational components complete.

---

## Phase 3: User Story 1 - Split-Screen Authentication Layout (Priority: P1) 🎯 MVP

**Goal**: Apply the split-screen design to primary authentication pages.

**Independent Test**: Navigate to `/login`, verify imagery hides on mobile and displays beside the form on desktop.

### Implementation for User Story 1

- [x] T005 [P] [US1] Refactor `resources/views/auth/login.blade.php` to consume the `<x-auth-split-layout>`. Replace existing button and input fields with Phase 009 `<x-button>` and `<x-input>` components.
- [x] T006 [P] [US1] Refactor `resources/views/auth/passwords/reset.blade.php` using `<x-auth-split-layout>` and the standardized input components.

**Checkpoint**: Login and reset pages are fully responsive using the new split-screen pattern.

---

## Phase 4: User Story 2 - Smooth Form Validation Feedback (Priority: P2)

**Goal**: Integrate the new validation component into auth forms for better UX.

**Independent Test**: Submit a blank login form and verify the error message fades in smoothly below the inputs.

### Implementation for User Story 2

- [x] T007 [P] [US2] Update `resources/views/auth/login.blade.php` to include the `<x-validation-feedback field="email">` and `field="password"` tags below their respective inputs. 
- [x] T008 [P] [US2] Implement inline `<x-validation-feedback>` within `resources/views/auth/passwords/reset.blade.php` to display validation or success states explicitly.

**Checkpoint**: Validation feedback is smooth and uniform.

---

## Phase 5: User Story 3 - Branded Error Pages (Priority: P3)

**Goal**: Implement custom error pages utilizing branded SVGs.

**Independent Test**: Visit a 404 route and verify the SVG custom styling matches the theme.

### Implementation for User Story 3

- [x] T009 [P] [US3] Add localized error strings for 403, 404, and 500 into `lang/en/errors.php` and `lang/ar/errors.php`.
- [x] T010 [P] [US3] Create `resources/views/errors/404.blade.php` using centered layout tokens. Use an inline SVG illustration and reference the `errors.404_title` lang keys.
- [x] T011 [P] [US3] Create `resources/views/errors/403.blade.php` with a distinct inline SVG and "Forbidden" localized text.
- [x] T012 [P] [US3] Create `resources/views/errors/500.blade.php` with a distinct inline SVG and server error localized text.

**Checkpoint**: 404, 403, and 500 error pages feel premium.

---

## Phase 6: User Story 4 - Visual Client Registration Polish (Priority: P3)

**Goal**: Overhaul the client registration page and add visual step tracking.

**Independent Test**: Navigate to `/register` and verify the split-screen appearance with a step indicator.

### Implementation for User Story 4

- [x] T013 [US4] Create the step tracker component in `resources/views/components/step-indicator.blade.php` utilizing active/inactive states based on the `$current` and `$total` props.
- [x] T014 [US4] Refactor `resources/views/auth/register.blade.php` to utilize the `<x-auth-split-layout>`, standard input fields, and inject the `<x-step-indicator>` component inside the form. Ensure logical padding constraints are respected.

**Checkpoint**: Client onboarding is modern and matches the visual footprint.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Test execution, RTL validations, and cleanup.

- [x] T015 Run automated view assertions in `php artisan test` (if applicable) to verify all forms render successfully.
- [x] T016 Visually test `login`, `register`, and `reset` pages toggling the default locale to Arabic (`ar`) to ensure CSS logical properties flip the components symmetrically.
- [x] T017 Run `npm run build` to finalize all Vite assets including the new Tailwind classes.

## Dependencies & Execution Order

- **Phase 2 Foundational** must be completed before implementing any auth forms.
- **US1** sets the standard and allows **US2** (Validation) to stack seamlessly.
- **US3** (Errors) and **US4** (Registration) can be worked on completely in parallel to US1/US2 as they modify independent files.
