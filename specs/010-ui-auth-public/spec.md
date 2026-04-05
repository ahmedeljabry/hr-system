---
description: "Authentication & Public Views Polish"
---

# Specification: Phase 010 - Authentication & Public Views Polish

## Overview

The application requires an overhaul of its initial touchpoints, including Login, Registration, Password Reset, and standard Error Pages (404, 403, 500). First impressions are critical for a premium SaaS HR product. This phase will transform these currently generic views into a visually stunning, split-screen layout with dynamic branding elements, smooth validation feedback, and an integrated, intuitive onboarding flow for new Clients registering their organizations.

## Clarifications

### Session 2026-04-05
- Q: How should the split-screen auth layout behave on mobile devices? → A: Hide the imagery panel entirely on mobile; show only the centered auth form
- Q: What type of visual treatment should the branded error pages (404, 403, 500) use? → A: Custom SVG illustrations — lightweight, themed line-art illustrations per error type

## User Scenarios

*   As a prospective Client, I want an inviting and premium registration experience so that I feel confident in the platform's quality.
*   As an existing User (Super Admin, Client, or Employee), I want a clear, branded, and smoothly animated login screen so that my daily entry into the tool feels professional.
*   As an unauthenticated User experiencing an error (like a 404 or 500), I want to see a branded, helpful error page that guides me back to safety rather than a generic server message.

## Functional Requirements

1.  **Auth Layouts:** Implement a modern, split-screen UI layout for Login and Registration views. One side should feature functional inputs, and the other should showcase dynamic brand imagery or value propositions.
2.  **Live Validation & Feedback:** Enhance authentication and onboarding forms to use the new atomic design system tokens and provide immediate, smooth visual feedback for errors or successful input.
3.  **Onboarding Enhancement:** Enhance the visual fidelity and flow of the new Client registration and organization setup process. Ensure it conveys a step-by-step logic visually.
4.  **Error Page Overhaul:** Replace default 404, 403, and 500 error pages with customized, branded screens utilizing correct HSL brand tokens, custom SVG line-art illustrations unique to each error type, and clear "Return to Home/Dashboard" CTAs.
5.  **Responsiveness (Mobile & Desktop):** On mobile devices, the split-screen layout MUST hide the imagery panel entirely and display only the centered auth form. On desktop, the full split-screen layout with brand imagery is shown.
6.  **Bilingual Support (RTL/LTR):** The new layouts must fully support English and Arabic translations using CSS logical properties seamlessly.

## Success Criteria

*   Users can navigate the Login and Registration flows on both mobile and desktop with zero layout breaks.
*   On mobile viewports, the imagery panel is hidden and only the centered auth form is displayed.
*   Both English (LTR) and Arabic (RTL) views of the authentication pages render symmetrically and flawlessly.
*   Standard HTTP error paths (e.g., manually visiting a non-existent route) successfully display the newly branded error screens with custom SVG illustrations instead of the generic Laravel defaults.

## Assumptions

*   The backend authentication endpoints and logic are already established; this is purely a UI/UX iteration.
*   We will re-use the foundation created in Phase 009 (Google Fonts, HSL colors, atomic blade components).
