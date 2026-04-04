# Phase 0: Research

## Unknowns & Key Decisions

### 1. Event Keys and Variable Registration
**Unknown**: How to securely define the seeded system events and their valid variable "cheat sheets" to display in the UI without hardcoding them in views?
**Decision**: Use a PHP 8.1+ Enum (`App\Enums\NotificationEvent`) to define the allowed system events.
**Rationale**: Enums provide strict type safety. We can add a method like `public function extractVariables(): array` directly to the Enum case, which the frontend can loop through to display the UI "cheat sheet". This keeps developer definitions DRY and centralized.
**Alternatives considered**: An `events.php` config array (less type-safe, error-prone when passing around strings) or storing keys in a separate DB table (violates the spec restriction that Admins cannot create completely new system events).

### 2. Variable Replacement Engine
**Unknown**: What is the safest and most performant way to parse and replace dynamic `{variable}` tokens inside a database string?
**Decision**: Leverage standard `str_replace` mapped to the payload array dynamically.
**Rationale**: Laravel's `__('key', ['name' => 'value'])` does this internally (replacing `:name`). We will replicate this exact regex/string replacement logic within a `ReminderPhraseService@render` method, but adopting the exact `{variable}` bracket syntax defined in the spec.
**Alternatives considered**: Passing variables via `eval()`, Blade direct string compilation (extremely unsafe and susceptible to XSS/Code Execution), regex captures (overkill for simple substitution).
