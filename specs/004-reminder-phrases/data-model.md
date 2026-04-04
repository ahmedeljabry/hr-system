# Data Model: Reminder Phrases

## `ReminderPhrase` (Table: `reminder_phrases`)

Centralized storage for administrator-managed, dynamically substituted templates. They act as a database-driven override for system notification language files.

### Attributes
- `id` (bigint, unsigned, auto-increment, primary key)
- `event_key` (string, unique) - e.g. `subscription.expiring`. Maps directly to backed Enum cases.
- `text_en` (text) - English template containing variables like `{days}`.
- `text_ar` (text) - Arabic template.
- `created_at` (timestamp)
- `updated_at` (timestamp)

### Associations
None. Reminder Phrases are totally global and untethered from any `client_id` (per FR-005).

## Software Artifacts & Enums

### `App\Enums\NotificationEvent` (Backed String Enum)
Defines the absolute list of developer-seeded event keys. 
- `public function availableVariables(): array` - Returns `['days', 'tenant_name']` etc., to display safely on the Admin UI cheat sheet.

### `App\Services\ReminderPhraseService`
Contains business logic for fetching, caching, and parsing templates:
- `public function getParsedMessage(NotificationEvent $event, array $payload): string`
  - Fetches from `ReminderPhrase` database table.
  - If null (missing config), safely returns `__('messages.{$event->value}', $payload)` via fallback.
  - If present, uses string regex/replace to map payload definitions into the `{variable}` substrings smoothly.
