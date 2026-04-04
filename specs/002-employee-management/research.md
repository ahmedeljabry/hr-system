# Phase 0: Research

## Unknowns & Decisions

1. **Excel Import Handling & Performance**
   - **Decision**: Use `Maatwebsite/Laravel-Excel` for bulk import. We will use the `ToModel`, `WithHeadingRow`, `WithValidation`, and `SkipsOnFailure` concerns to achieve the "partial success" mode required by the spec.
   - **Rationale**: It natively supports Laravel, validation, and skipping failed rows (`SkipsOnFailure`), which directly satisfies the FR-011 requirement of returning a row-numbered error report while importing valid rows.
   - **Alternatives considered**: Writing a custom parser using `PhpSpreadsheet` directly (too much boilerplate), or using a simpler CSV parser (doesn't support `.xlsx` natively as requested).

2. **File Storage & Security**
   - **Decision**: Store National ID and Contract images in `storage/app/private/employees`. Create a dedicated route/controller (`EmployeeFileController`) to serve these files, protected by `auth` and `role` middleware, checking tenant ownership before returning the file via `response()->file()`.
   - **Rationale**: Meets FR-003 and SC-004 requirements for secure, non-publicly accessible files and tenant isolation.
   - **Alternatives considered**: Storing in database as BLOBs (bad for performance and Laravel ecosystem standards).

3. **Tenant Isolation Strategy**
   - **Decision**: Use a global scope or a trait (e.g., `BelongsToTenant`) on the `Employee` model to automatically apply `where('client_id', auth()->user()->client_id)`.
   - **Rationale**: Ensures SC-003 (100% tenant isolation) is met across all queries without manually adding the where clause to every eloquent call.
   - **Alternatives considered**: Manually appending `where('client_id', ...)` in every controller/service (prone to human error).

4. **Subscription Expiry Banner**
   - **Decision**: Create a View Composer or a global middleware that shares a `$subscriptionWarning` variable to all client views if `subscription_end` is <= 7 days from today.
   - **Rationale**: Allows the banner to be displayed on the dashboard (or globally if desired) efficiently without duplicating logic in multiple controllers.

5. **Soft Deletes for Employees**
   - **Decision**: Use Laravel's native `SoftDeletes` trait on the `Employee` model.
   - **Rationale**: Satisfies FR-014 (archive employees, exclude from active lists) natively without writing custom scopes.

6. **Bilingual Support (Arabic/English)**
   - **Decision**: Use Laravel's localization features (`lang/ar` and `lang/en`). Store translations for validation messages, UI labels, and error reports. Ensure the frontend layout reacts to the `app()->getLocale()` to switch between RTL and LTR (e.g., using `dir="rtl"` in the HTML tag).
   - **Rationale**: Directly satisfies FR-009.
