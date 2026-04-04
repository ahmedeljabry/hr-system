# Feature Specification: Reminder Phrases

**Feature Branch**: `004-reminder-phrases`  
**Created**: 2026-04-05  
**Status**: Draft  
**Input**: User description: "create specifications for all reminder phrases"

## Clarifications

### Session 2026-04-05
- Q: Can Super Admins invent completely new system events in the UI, or are they only mapping phrases to pre-programmed event keys (seeds) provided by developers? → A: Admins only configure phrases for developer-seeded event keys.
- Q: How do admins know which dynamic variables are safe and valid to use in the text box for a given event? → A: The UI automatically displays a "cheat sheet" of valid variables for the selected event key.
- Q: What should happen if a system event triggers a notification, but the Super Admin has not configured or deleted the ReminderPhrase? → A: Fallback to hardcoded Laravel locale files (`__('messages.key')`).

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Reminder Template Management (Priority: P1)

As an administrator, I want to manage and configure a library of automated reminder phrases so that the system effectively communicates notifications (such as subscription expiry and payroll deadlines) using customized professional language.

**Why this priority**: Without defined templates, notifications cannot be properly localized or customized per tenant/business needs.

**Independent Test**: Can be fully tested by an admin navigating to the system settings, creating a new "Subscription Expiry" reminder phrase in both Arabic and English, saving it, and verifying it renders correctly in a preview.

**Acceptance Scenarios**:

1. **Given** an authenticated admin viewing the Reminder Phrase settings, **When** they add a new phrase with specific dynamic variables (e.g., `{days_remaining}`), **Then** the phrase is securely stored and available for the notification engine.
2. **Given** an existing reminder phrase, **When** an admin modifies the Arabic translation, **Then** the system uses the new translation on subsequent rendered warnings.

---

### User Story 2 - Automated Delivery of Reminders (Priority: P2)

As a client or employee, I want to receive proactive system reminders configured by the administrators so I am aware of approaching deadlines or critical changes.

**Why this priority**: The configured phrases deliver no value unless dynamically triggered and rendered to the correct audience.

**Independent Test**: Can be fully tested by simulating a subscription expiry 5 days away. Upon login, the client dashboard should fetch the correct reminder phrase and render the alert natively.

**Acceptance Scenarios**:

1. **Given** a client with a subscription expiring in 3 days, **When** they access their dashboard, **Then** the system retrieves the "Subscription Expiry" reminder phrase, evaluates the variables, and displays the contextual warning.

## Edge Cases & Error Handling

- **Missing Configuration**: If a system event triggers but no `ReminderPhrase` exists in the database for that key, the system MUST gracefully fall back to a hardcoded language file response (e.g., `__('messages.key')`) to ensure critical warnings are not lost.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide an interface to create, read, update, and delete (CRUD) reminder phrase templates natively for predefined, developer-seeded event keys only (admins cannot arbitrarily invent new system events).
- **FR-002**: System MUST support localized inputs for each phrase (e.g., separate text bodies for Arabic and English variants).
- **FR-003**: System MUST support dynamic variables inside the phrase string (e.g. replacing `{user_name}`), and the UI MUST inherently display a reference list of valid available variables mapped to the currently selected event key.
- **FR-004**: System MUST trigger Reminder Phrases for in-app UI display only (e.g., dashboard banners), with no external delivery mechanisms like Email or SMS.
- **FR-005**: System MUST enforce that these automated reminder templates are configured globally by Super Admins only to ensure brand consistency.

### Key Entities

- **Reminder Phrase**: A dictionary template bridging a system event (e.g. `subscription.expiring`) with a localized text body supporting dynamic variables. 

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admins can complete the creation of a new bilingual reminder phrase in under 2 minutes.
- **SC-002**: System successfully resolves dynamic payload variables within 50ms before rendering on the frontend.
- **SC-003**: The architecture allows arbitrary new events to adopt a reminder phrase with zero database schema changes.

## Assumptions

- We are building a robust Notification Phrases/Templates engine to replace hardcoded language files.
- These phrases are read frequently so database/cache read optimization is implicitly handled at the framework level.
