# Quickstart: Employee Portal

**Feature**: 006-employee-portal  
**Date**: 2026-04-05

## Prerequisites

- Phases 1–3, 5 are implemented (Foundation, Employees, Payroll, Attendance/Tasks/Assets)
- Database migrated with `php artisan migrate`
- At least one Client with active subscription and one Employee with linked user account

## Key Components

### Routes (employee.php additions)

```
GET  /employee/dashboard          → Employee\DashboardController@index
GET  /employee/profile            → Employee\ProfileController@index
GET  /employee/profile/documents/{type} → Employee\ProfileController@document
GET  /employee/announcements      → Employee\AnnouncementController@index
GET  /employee/leaves             → Employee\LeaveController@index
```

### Routes (client.php additions)

```
GET    /client/announcements            → Client\AnnouncementController@index
GET    /client/announcements/create     → Client\AnnouncementController@create
POST   /client/announcements            → Client\AnnouncementController@store
GET    /client/announcements/{id}/edit  → Client\AnnouncementController@edit
PUT    /client/announcements/{id}       → Client\AnnouncementController@update
DELETE /client/announcements/{id}       → Client\AnnouncementController@destroy
```

### Middleware Stack

Employee routes: `['auth', 'role:employee', 'check_subscription']`

### Services

- **DashboardService**: `getWidgetData(Employee $employee): array` — returns counts/latest for tasks, assets, payslips, leave, announcements
- **AnnouncementService**: `create(Client $client, array $data)`, `update(Announcement $ann, array $data)`, `delete(Announcement $ann)`, `getForClient(Client $client, int $perPage = 10)`

### Views Hierarchy

```
layouts/employee.blade.php          ← sidebar + main area
employee/dashboard.blade.php        ← summary widgets
employee/profile/index.blade.php    ← personal info + docs
employee/announcements/index.blade.php ← paginated feed
employee/leaves/index.blade.php     ← scaffold (empty state)
client/announcements/index.blade.php   ← management table
client/announcements/create.blade.php  ← create form
client/announcements/edit.blade.php    ← edit form
```

## Quick Validation

```bash
# Run Phase 6 tests
php artisan test --filter=DashboardTest
php artisan test --filter=ProfileTest
php artisan test --filter=AnnouncementTest
php artisan test --filter=AnnouncementVisibilityTest

# Run all tests
php artisan test
```
