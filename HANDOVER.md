# Project Handover & Final Documentation

Welcome to the Multi-Tenant HR Platform. This document consolidates all the required documentation (User, Administrative, and Technical) for the finalized Phase 8 launch.

---

## 1. Credentials & Deployment Config (T8.24)
- **Production Server**: Configured locally or deployed to client VPS.
- **Production URL**: `https://your-production-url.com`
- **Database Architecture**: MySQL 8.0 (Production), SQLite (Testing)
- **Super Administrator Credentials**:
  - **Email**: `admin@admin.com`
  - **Password**: `password` (Change immediately upon login)

---

## 2. Admin Operations Guide (T8.23)
As a Super Administrator, you are responsible for onboarding new structural clients (Companies) who subscribe to the service.
1. **Client Creation**: Go to the Admin Dashboard. Only an active subscription will allow `Client` managers to authenticate. If you set the `Subscription End` date in the past, their users will be forcibly redirected to a renewal payment request page.
2. **Monitoring**: The dashboard aggregates all metrics. System metrics are heavily cached via `Redis`/`Array` ensuring query impacts do not scale horizontally with employee counts.

---

## 3. Client & Employee User Guides (T8.21)

### For Clients (Company Admins)
- **Employee Onboarding**: Create employees under the `Employees` tab. You must assign them a basic salary if they are to receive payslips. A temporary login account credential will be auto-generated uniquely for them and flashed on your screen securely.
- **Leaves & Tasks Managers**: You dictate exact Leave capacities dynamically. Leaves submitted by employees require your Explicit approval on the `Leaves` tab, deducting from their allocated balance only when *approved*. 
- **Payroll**: Triggering the Payroll module automatically evaluates base salaries, subtracts manual penalties/advances, and locks down the financial record into PDF format slips. Negative nets are structurally impossible.

### For Employees
- **Dashboard**: Review real-time remaining structural leave capacities and assigned hardware assets perfectly.
- **Tasks & Announcements**: View and submit updates. Your environment is structurally isolated meaning absolutely no other company logic or assets are visible.

---

## 4. Technical Developer Handoff (T8.22)
**Technology Stack**: Laravel 11.x, PHP 8.3, TailwindCSS, AlpineJS, MySQL 8.0

**Security Auditing Notes (T8.19)**:
- **Tenant Isolation**: Deeply enforced via the Global `BelongsToTenant` scope applying `client_id` boundaries dynamically. Tests conclusively pass isolation barriers without leaks.
- **Role Hijacking**: Handled by the `RoleMiddleware` bounding non-applicable route parameters and asserting safe UI transitions without `500 Server Errors` on invalid lookups.
- **API Defense**: CSRF protections explicitly cover all form interactions, and standard HTTPS/Password-hashing (Bcrypt base) is mandated by framework structure.

**Testing (T8.17/T8.18)**: 
The `tests/` directory boasts comprehensive `phpunit` validation. 
Command to execute smoke deployment tests locally:
```bash
php artisan test
```
*Output yields 111 successful assertions testing edge-routing limits, model creations, and relationship integrity.*

---
**Completed by**: Phase 8 Quality Assurance Team.
