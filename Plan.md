# HR Management System — Spec-Driven Development Plan
> Following [github/spec-kit](https://github.com/github/spec-kit) SDD workflow
> **Workflow:** Constitution → Specify → Plan → Tasks → Implement

---

## Project Overview

| | |
|---|---|
| **Project** | Multi-tenant HR Management System |
| **Budget** | $150.00 |
| **Timeline** | 10 days |
| **AI Tool** | Claude Code (`/speckit-*` skills) |
| **Methodology** | Spec-Driven Development (SDD) |

---

## SDD Workflow Map

```
📄 constitution.md          ← Non-negotiable principles (run once)
        ↓
📋 /speckit-specify          ← What we're building (features + user flows)
        ↓
📐 /speckit-plan             ← How we build it (architecture + tech decisions)
        ↓
✅ /speckit-tasks            ← Atomic tasks AI agents will execute
        ↓
⚙️  /speckit-implement       ← AI generates code per task (loop per phase)
```

---

## Setup (Run Once)

```bash
# Install spec-kit CLI
uv tool install specify-cli --from git+https://github.com/github/spec-kit.git@v0.4.5

# Scaffold the project
specify init hr-system

# Claude Code will expose these skills:
# /speckit-constitution  /speckit-specify  /speckit-plan  /speckit-tasks  /speckit-implement
```

---

## constitution.md (Non-Negotiable Rules)

> Create this file first. Every AI command will refer back to it.

```markdown
# HR System — Constitution

## Stack (Non-Negotiable)
- Backend: Laravel 11 (PHP 8.3)
- Frontend: Blade + Alpine.js (or React for SPA sections)
- Database: MySQL 8
- Auth: Laravel Sanctum with role-based access (super_admin / client / employee)
- Excel import/export: Maatwebsite/Laravel-Excel

## Architecture Principles
- Multi-tenant: every DB query scoped to client_id — never expose cross-tenant data
- Role-guard every route and controller method
- All business logic lives in Service classes, NOT controllers
- TDD: write feature tests before implementation (PHPUnit)
- CLI-first: all major operations exposable via Artisan commands

## Security (Non-Negotiable)
- Passwords hashed with bcrypt
- No raw SQL — use Eloquent ORM only
- CSRF protection on all forms
- File uploads validated and stored in private disk (not public)

## Code Style
- PSR-12 for PHP
- Repository pattern for DB access
- No logic in Blade views — data comes pre-formatted from controllers/view-models
```

---

---

# Phase 1 — Foundation & Auth
> **Days 1–2** | `/speckit-specify` → `/speckit-plan` → `/speckit-tasks` → `/speckit-implement`

## Specify (What)

**Features:**
- User registration with role assignment (super_admin / client / employee)
- Secure login / logout with session management
- Role-based route protection (middleware)
- Client subscription record: start date, end date, status (active / expired / suspended)
- Super Admin can view all clients and toggle subscription status

**User Flows:**
```
[Client] → Register → Creates: users row (role=client) + clients row (subscription info)
[Employee] → Created BY client, NOT self-registered → users row (role=employee)
[Super Admin] → Seeded manually → Full access to all tenants
```

## Plan (How)

**Database:**
```sql
users          (id, name, email, password, role ENUM, remember_token, timestamps)
clients        (id, user_id FK, company_name, subscription_start, subscription_end,
                status ENUM[active,expired,suspended], timestamps)
```

**Key Files:**
```
app/Models/User.php              ← hasOne Client / hasOne Employee
app/Models/Client.php            ← belongsTo User
app/Http/Middleware/RoleMiddleware.php
app/Services/AuthService.php
app/Services/SubscriptionService.php
database/migrations/
database/seeders/SuperAdminSeeder.php
```

## Tasks (Atomic)

- [ ] **T1.1** — Create migration: `users` table with role ENUM
- [ ] **T1.2** — Create migration: `clients` table with subscription fields
- [ ] **T1.3** — Build `RoleMiddleware` and register in `bootstrap/app.php`
- [ ] **T1.4** — Build `AuthService`: register, login, logout methods
- [ ] **T1.5** — Build `SubscriptionService`: check expiry, toggle status
- [ ] **T1.6** — Build Auth controllers + Blade views (login, register)
- [ ] **T1.7** — Seed `SuperAdminSeeder`
- [ ] **T1.8** — Feature tests: login flow, role guard, subscription check

---

---

# Phase 2 — Client Dashboard & Employee Management
> **Days 3–4** | Repeat SDD loop for this phase

## Specify (What)

**Features:**
- Client sees their own dashboard after login
- Client can add / edit / delete employees
- Client can import employees from `.xlsx` file (bulk)
- Each employee record: name, position, national ID image, contract image, salary
- Subscription expiry warning banner on dashboard

**User Flows:**
```
[Client] → Dashboard → Employees → Add Employee (form)
[Client] → Dashboard → Employees → Import (upload .xlsx)
[Client] → Dashboard → Subscription status widget
```

## Plan (How)

**Database:**
```sql
employees  (id, client_id FK, user_id FK NULL, name, position, national_id_img,
            contract_img, basic_salary, hire_date, timestamps)
```

**Key Files:**
```
app/Models/Employee.php
app/Services/EmployeeService.php
app/Imports/EmployeesImport.php          ← Maatwebsite import class
app/Http/Controllers/Client/EmployeeController.php
resources/views/client/employees/
storage/app/private/employees/           ← private disk for ID/contract files
```

**Excel Import Columns:** `name | position | national_id | basic_salary | hire_date`

## Tasks (Atomic)

- [ ] **T2.1** — Create migration: `employees` table
- [ ] **T2.2** — Build `EmployeeService`: CRUD + scope to `client_id`
- [ ] **T2.3** — Build `EmployeesImport` class with validation rules
- [ ] **T2.4** — Build `EmployeeController` (index, create, store, edit, update, destroy, import)
- [ ] **T2.5** — Blade views: employee list table, add/edit form, import form
- [ ] **T2.6** — File upload handling (ID image, contract) → private disk
- [ ] **T2.7** — Subscription expiry banner component (check via middleware/view composer)
- [ ] **T2.8** — Feature tests: employee CRUD, Excel import, cross-tenant isolation

---

---

# Phase 3 — Payroll & Benefits
> **Days 5–6** | Repeat SDD loop

## Specify (What)

**Features:**
- Client defines salary components per employee: basic, allowances, deductions
- Monthly payroll run: generates net salary record per employee
- Client views payroll history by month
- Employee can view their own payslip (read-only)

**User Flows:**
```
[Client] → Payroll → Select Month → Run Payroll → Review → Confirm
[Employee] → My Payslip → Select Month → View breakdown
```

## Plan (How)

**Database:**
```sql
salary_components  (id, employee_id FK, type ENUM[allowance,deduction], name, amount, timestamps)
payroll_runs       (id, client_id FK, month DATE, status ENUM[draft,confirmed], run_at, timestamps)
payslips           (id, payroll_run_id FK, employee_id FK, basic, total_allowances,
                    total_deductions, net_salary, timestamps)
```

**Key Files:**
```
app/Services/PayrollService.php          ← calculateNet(), runPayroll(), getPayslip()
app/Models/PayrollRun.php
app/Models/Payslip.php
app/Http/Controllers/Client/PayrollController.php
app/Http/Controllers/Employee/PayslipController.php
resources/views/client/payroll/
resources/views/employee/payslip/
```

## Tasks (Atomic)

- [ ] **T3.1** — Migrations: `salary_components`, `payroll_runs`, `payslips`
- [ ] **T3.2** — Build `PayrollService::calculateNet(employee_id)` with unit tests
- [ ] **T3.3** — Build `PayrollService::runPayroll(client_id, month)` — bulk run
- [ ] **T3.4** — Client payroll controller + views (run payroll, history table)
- [ ] **T3.5** — Employee payslip controller + view (read-only breakdown)
- [ ] **T3.6** — Feature tests: payroll calculation, monthly run, employee access

---

---

# Phase 4 — Leave Management
> **Day 5–6 (parallel with Payroll)** | Repeat SDD loop

## Specify (What)

**Features:**
- Client defines leave types (annual, emergency, sick, unpaid, custom)
- Employee submits leave request: type, date range, reason
- Client approves or rejects with optional comment
- Employee sees leave balance and history
- Approved leave deducted from balance automatically

**User Flows:**
```
[Employee] → Leaves → Apply → (select type + dates + reason) → Submitted
[Client]   → Leaves → Pending requests → Approve / Reject (+ comment)
[Employee] → Leaves → My balance → Shows remaining per type
```

## Plan (How)

**Database:**
```sql
leave_types     (id, client_id FK, name, max_days_per_year, timestamps)
leave_balances  (id, employee_id FK, leave_type_id FK, year, used_days, timestamps)
leave_requests  (id, employee_id FK, leave_type_id FK, start_date, end_date,
                 reason, status ENUM[pending,approved,rejected], reviewer_comment, timestamps)
```

**Key Files:**
```
app/Services/LeaveService.php
app/Models/LeaveRequest.php
app/Http/Controllers/Client/LeaveController.php
app/Http/Controllers/Employee/LeaveRequestController.php
```

## Tasks (Atomic)

- [ ] **T4.1** — Migrations: `leave_types`, `leave_balances`, `leave_requests`
- [ ] **T4.2** — Build `LeaveService`: submit, approve, reject, getBalance
- [ ] **T4.3** — Auto-deduct balance on approval (observer or service method)
- [ ] **T4.4** — Client: pending requests list + approve/reject action
- [ ] **T4.5** — Employee: apply form, balance widget, history table
- [ ] **T4.6** — Feature tests: apply flow, approval, balance deduction, rejection

---

---

# Phase 5 — Attendance, Tasks & Assets
> **Day 7** | Repeat SDD loop

## Specify (What)

**Features:**
- Client records daily attendance per employee: present / absent / late + notes
- Client creates tasks: title, description, assigned employee, due date, status
- Employee views assigned tasks and their status
- Client records assets assigned to employees (car, device, etc.)
- Employee views their own assets

## Plan (How)

**Database:**
```sql
attendance  (id, employee_id FK, date DATE, status ENUM[present,absent,late], notes, timestamps)
tasks       (id, client_id FK, employee_id FK NULL, title, description,
             status ENUM[todo,in_progress,done], due_date, timestamps)
assets      (id, employee_id FK, asset_type, description, assigned_date, returned_date NULL, timestamps)
```

## Tasks (Atomic)

- [ ] **T5.1** — Migrations: `attendance`, `tasks`, `assets`
- [ ] **T5.2** — Attendance: client bulk-entry form (date + all employees in a table row)
- [ ] **T5.3** — Tasks: client CRUD, assign to employee, status update
- [ ] **T5.4** — Employee: view my tasks (filtered by status)
- [ ] **T5.5** — Assets: client assign/return, employee view my assets
- [ ] **T5.6** — Feature tests for all three modules

---

---

# Phase 6 — Employee Portal
> **Day 8** | Repeat SDD loop

## Specify (What)

**Features (Employee-facing — all read or request only):**
- Dashboard: name, position, leave balance summary, upcoming tasks
- Profile: contract image, national ID image (view only)
- Payslips history
- Leave: balance + request form + history
- Tasks: my tasks with status
- Assets: my assigned assets
- Announcements: company announcements from client

**User Flow:**
```
[Employee] logs in → Employee Dashboard
  ├── My Profile (docs, basic info)
  ├── My Payslips (monthly list → detail)
  ├── My Leaves (balance + apply + history)
  ├── My Tasks (list + status)
  ├── My Assets (list)
  └── Announcements (company feed)
```

## Plan (How)

**New table needed:**
```sql
announcements  (id, client_id FK, title, body, published_at, timestamps)
```

**Key Files:**
```
app/Http/Controllers/Employee/DashboardController.php
app/Http/Controllers/Employee/ProfileController.php
app/Http/Controllers/Employee/AnnouncementController.php
resources/views/employee/                ← all employee portal views
routes/employee.php                      ← grouped under auth + role:employee middleware
```

## Tasks (Atomic)

- [ ] **T6.1** — Migration: `announcements`
- [ ] **T6.2** — Employee dashboard: summary widgets (leave balance, pending tasks count)
- [ ] **T6.3** — Profile view: show contract + ID images via signed URL (private disk)
- [ ] **T6.4** — Wire all existing services into employee-facing controllers
- [ ] **T6.5** — Client: announcement CRUD (create, publish, delete)
- [ ] **T6.6** — Employee: announcements feed
- [ ] **T6.7** — Feature tests: employee can only see own data, cannot access other tenants

---

---

# Phase 7 — Super Admin Dashboard
> **Day 9** | Repeat SDD loop

## Specify (What)

**Features:**
- View all registered clients (company name, subscription status, employee count)
- Toggle subscription status (active / suspended / expired)
- View employees under any client
- Edit any user's basic info (name, email)
- System-wide stats: total clients, total employees, active subscriptions

## Plan (How)

**No new migrations needed — reads across existing tables.**

**Key Files:**
```
app/Http/Controllers/Admin/ClientController.php
app/Http/Controllers/Admin/EmployeeController.php
app/Services/AdminStatsService.php
resources/views/admin/
routes/admin.php                         ← grouped under auth + role:super_admin middleware
```

## Tasks (Atomic)

- [ ] **T7.1** — Admin clients list: sortable table with stats columns
- [ ] **T7.2** — Admin client detail: employees list + subscription controls
- [ ] **T7.3** — `AdminStatsService`: aggregate counts for dashboard widgets
- [ ] **T7.4** — Admin edit user: name, email (not role, not password directly)
- [ ] **T7.5** — Feature tests: super_admin access, client cannot access admin routes

---

---

# Phase 8 — QA, Polish & Deployment
> **Day 10** | Final pass

## Tasks (Atomic)

- [ ] **T8.1** — Run full test suite: `php artisan test` — fix all failures
- [ ] **T8.2** — Check cross-tenant isolation: attempt to access another client's data → should 403
- [ ] **T8.3** — Subscription expiry: expired client redirected to renewal page
- [ ] **T8.4** — UI polish: responsive check, empty states, loading indicators
- [ ] **T8.5** — `php artisan optimize`, config cache, route cache for production
- [ ] **T8.6** — Deploy to server: `.env` production config, run migrations, seed super admin
- [ ] **T8.7** — Smoke test all 3 roles on live server
- [ ] **T8.8** — Hand off: credentials, basic usage doc, repo access

---

---

## Phases Summary

| Phase | Scope | Days | Key Deliverable |
|---|---|---|---|
| **1** | Foundation & Auth | 1–2 | Login, roles, subscription |
| **2** | Employee Management | 3–4 | CRUD + Excel import |
| **3** | Payroll & Benefits | 5–6 | Payroll run, payslips |
| **4** | Leave Management | 5–6 | Leave requests + approval |
| **5** | Attendance, Tasks, Assets | 7 | 3 modules |
| **6** | Employee Portal | 8 | Full employee-facing UI |
| **7** | Super Admin | 9 | Admin dashboard |
| **8** | QA & Deployment | 10 | Live on server |

---

## SDD Loop (Repeat Per Phase)

```
1. /speckit-specify   → describe WHAT this phase builds
2. /speckit-plan      → AI generates HOW (files, DB, architecture)
3. /speckit-tasks     → AI breaks plan into atomic tasks
4. /speckit-implement → AI implements task by task (TDD: test first)
5. Review → update spec if needed → next phase
```

---

> Note: The system supports Arabic and English with a language switcher (RTL/LTR)

---

## Localization & Multi-Language Support

**Supported languages:** Arabic (RTL) + English (LTR)

### Requirements:

- The system must support **two languages**: Arabic and English
- Default language: Arabic
- Users can switch language باستخدام زرار واضح في الـ UI (Language Switcher)
- اللغة المختارة يتم حفظها (session أو database) بحيث تفضل ثابتة

### UI Behavior:

- عند اختيار العربية:
  - الاتجاه يكون **RTL**
  - كل النصوص بالعربي

- عند اختيار الإنجليزية:
  - الاتجاه يكون **LTR**
  - كل النصوص بالإنجليزي

### Implementation Notes:

- استخدام Laravel localization (`resources/lang/{locale}`)
- استخدام ملفات ترجمة:
  - `resources/lang/ar/`
  - `resources/lang/en/`
- كل النصوص في الواجهة لازم تكون dynamic باستخدام `__('key')`
- Middleware لتحديد اللغة الحالية من:
  - session أو user preference

### Language Switcher:

- زرار في الـ Navbar أو الـ Header
- مثال:
  - زرار: "AR | EN"
- عند الضغط:
  - يتم تغيير اللغة فورًا
  - إعادة تحميل الصفحة بالـ locale الجديد

### Database & Content:

- دعم UTF-8 لتخزين العربي
- الحقول النصية (زي announcements) ممكن تكون:
  - إما بلغة واحدة حسب المستخدم
  - أو multilingual (اختياري مستقبلاً)

---
## Notes

- Specs are **living documents** — update them if requirements change, then re-run `/speckit-plan`
- Each phase is an independent Git branch: `feature/phase-1-auth`, `feature/phase-2-employees`, etc.
- Never skip tests — the constitution mandates TDD
- Any scope changes outside this plan are billable separately
