# Research: Foundation & Authentication

**Feature**: `001-foundation-auth`
**Date**: 2026-04-04

## R1: Laravel 11 Authentication with Sanctum

**Decision**: Use Laravel Sanctum for session-based web authentication
(not API token auth). Sanctum's session guard is ideal for Blade-based
apps with CSRF protection built-in.

**Rationale**: Sanctum supports both SPA and traditional web auth.
Since we use Blade (not a SPA), we use Sanctum's session-based web
guard which leverages Laravel's built-in session driver. This gives
us CSRF protection automatically.

**Alternatives considered**:
- Laravel Breeze: Would scaffold UI but overrides our Arabic RTL
  requirements. Discarded.
- Laravel Fortify: Headless auth — possible but adds complexity for
  our simple needs. Discarded.
- Manual auth: Too error-prone. Discarded.

## R2: Role-Based Middleware Pattern

**Decision**: Single `RoleMiddleware` that accepts comma-separated
roles and checks `Auth::user()->role` against the allowed list.
Registered as `role` alias in `bootstrap/app.php`.

**Rationale**: Laravel 11 removed the HTTP Kernel. Middleware is
registered in `bootstrap/app.php` via `->withMiddleware()`. A single
flexible middleware is simpler than per-role middleware classes.

**Usage**:
```php
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // admin routes
});
```

**Alternatives considered**:
- Laravel Policies/Gates: Better for object-level authorization, not
  route-level role guards. Will use in later phases for fine-grained
  permissions.
- Spatie laravel-permission: Powerful but overkill for 3 fixed roles.
  Adds unnecessary dependency.

## R3: Multi-Tenant Scoping Strategy

**Decision**: Use a `BelongsToTenant` trait with a global Eloquent
scope that automatically adds `WHERE client_id = ?` to all queries.
The `client_id` is resolved from `Auth::user()->client->id` or
`Auth::user()->employee->client_id`.

**Rationale**: Global scopes ensure tenant isolation at the model
level — impossible to accidentally leak data even if a developer
forgets to add a filter. Constitution Principle I mandates this.

**Alternatives considered**:
- Manual `where('client_id', ...)` everywhere: Error-prone, violates
  Constitution Principle I.
- Separate databases per tenant: Over-engineered for this scale.
- Stancl/Tenancy package: Too heavy for 3 tables.

## R4: Subscription Status Middleware

**Decision**: Create `CheckSubscription` middleware that runs after
auth. It checks `Auth::user()->client->status` — if not 'active',
redirects to the subscription renewal page.

**Rationale**: Checking subscription on every request ensures that
even if a client's subscription is suspended mid-session, they
cannot access protected resources on their next navigation.

**Post-auth middleware stack for client routes**:
```
auth → role:client → check_subscription
```

## R5: Progressive Rate Limiting

**Decision**: Use Laravel's built-in `RateLimiter` with a custom
limiter that tracks failed attempts and escalates lockout duration.
Store attempt counts in cache (database or file driver).

**Rationale**: Laravel's `ThrottleRequests` middleware supports
custom rate limiters defined in `AppServiceProvider`. We'll create
a `login` limiter with escalating decay:
- 5 attempts → 5 min lockout
- 10 attempts → 15 min lockout
- 15 attempts → 30 min lockout
- 20+ attempts → 60 min lockout

Keyed by email + IP to prevent locking out legitimate users sharing
an IP.

**Alternatives considered**:
- Fixed rate limit: Doesn't match the spec requirement for escalation.
- Third-party package: Unnecessary — Laravel built-in is sufficient.

## R6: Bilingual RTL/LTR Layout Strategy

**Decision**: Single `app.blade.php` layout with dynamic `<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">`. Use CSS logical properties (`margin-inline-start`, etc.). Use a `LanguageController` to toggle session locale and a `SetLocale` middleware to apply it.

**Rationale**: Constitution Principle V mandates Bilingual support with Arabic
as default. A single layout file ensures consistency while handling
dynamic directionality. Laravel's localization handles translated strings.

**Key implementation details**:
- Set `'locale' => 'ar'` in `config/app.php` as default
- Create `SetLocale` middleware pushing session locale to `app()->setLocale()`
- Create `resources/lang/ar/` and `resources/lang/en/` directories
- Error messages rendered dynamically via custom validation translation files

## R7: Remember Me Token Duration

**Decision**: Set `'expire_on_close' => false` in session config
and configure remember token lifetime to 30 days (43200 minutes)
in `config/auth.php` → `'remember'` duration.

**Rationale**: Clarification Q3 confirmed 30 days. Laravel's built-in
remember functionality handles this via `remember_token` column on
the `users` table.
