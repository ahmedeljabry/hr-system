# Route Contracts: Foundation & Authentication

**Feature**: `001-foundation-auth`
**Date**: 2026-04-04

## Public Routes (no auth required)

| Method | URI | Controller | Action | Description |
|--------|-----|-----------|--------|-------------|
| GET | `/login` | `LoginController` | `showLoginForm` | صفحة تسجيل الدخول |
| POST | `/login` | `LoginController` | `login` | معالجة تسجيل الدخول |
| GET | `/register` | `RegisterController` | `showRegistrationForm` | صفحة التسجيل |
| POST | `/register` | `RegisterController` | `register` | معالجة التسجيل |
| GET | `/lang/{locale}` | `LanguageController` | `switchLang` | تبديل لغة الواجهة |

## Authenticated Routes (auth required)

| Method | URI | Controller | Action | Middleware | Description |
|--------|-----|-----------|--------|------------|-------------|
| POST | `/logout` | `LogoutController` | `logout` | `auth` | تسجيل الخروج |

## Admin Routes (auth + role:super_admin)

| Method | URI | Controller | Action | Description |
|--------|-----|-----------|--------|-------------|
| GET | `/admin/dashboard` | `AdminDashboardController` | `index` | لوحة المدير |
| GET | `/admin/clients` | `ClientController` | `index` | قائمة العملاء |
| PATCH | `/admin/clients/{client}/status` | `ClientController` | `updateStatus` | تبديل حالة الاشتراك |
| PATCH | `/admin/clients/{client}/subscription` | `ClientController` | `updateSubscription` | تحديد تاريخ انتهاء الاشتراك |

## Client Routes (auth + role:client + check_subscription)

| Method | URI | Controller | Action | Description |
|--------|-----|-----------|--------|-------------|
| GET | `/client/dashboard` | `ClientDashboardController` | `index` | لوحة العميل |

## Employee Routes (auth + role:employee)

| Method | URI | Controller | Action | Description |
|--------|-----|-----------|--------|-------------|
| GET | `/employee/dashboard` | `EmployeeDashboardController` | `index` | لوحة الموظف |

## Subscription Routes (auth, subscription inactive)

| Method | URI | Controller | Action | Description |
|--------|-----|-----------|--------|-------------|
| GET | `/subscription/renewal` | `SubscriptionController` | `renewal` | صفحة تجديد الاشتراك |

## Middleware Stack Summary (All routes include `web` and `set_locale`)

```text
Public:         web, set_locale
Authenticated:  auth
Admin:          auth → role:super_admin
Client:         auth → role:client → check_subscription
Employee:       auth → role:employee
```

## Request/Response Contracts

### POST /register

**Request body**:
```json
{
  "name": "string, required, max:255",
  "email": "string, required, email, unique:users",
  "password": "string, required, min:8",
  "password_confirmation": "string, required, must match password",
  "company_name": "string, required, max:255"
}
```

**Success**: Redirect → `/client/dashboard` (302)
**Failure**: Redirect back with validation errors (422 equivalent)

### POST /login

**Request body**:
```json
{
  "email": "string, required, email",
  "password": "string, required",
  "remember": "boolean, optional"
}
```

**Success**: Redirect → role-based dashboard (302)
- super_admin → `/admin/dashboard`
- client (active) → `/client/dashboard`
- client (inactive) → `/subscription/renewal`
- employee → `/employee/dashboard`

**Failure**: Redirect back with error message (422 equivalent)
**Rate limited**: Redirect back with lockout message + retry time

### PATCH /admin/clients/{client}/status

**Request body**:
```json
{
  "status": "string, required, in:active,expired,suspended"
}
```

**Success**: Redirect back with success notification (302)
**Failure**: 403 (not admin) or 422 (validation error)

### PATCH /admin/clients/{client}/subscription

**Request body**:
```json
{
  "subscription_end": "date, required, after:today"
}
```

**Success**: Redirect back with success notification (302)
**Failure**: 403 (not admin) or 422 (validation error)

### GET /lang/{locale}

**Path Parameter**:
- `locale`: string, required, in: ar, en

**Success**: Sets locale in session and redirects back. If invalid locale, defaults to `ar` and redirects back.
