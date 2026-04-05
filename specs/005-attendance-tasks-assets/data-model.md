# Data Model: Operations Management

**Branch**: `005-attendance-tasks-assets` | **Date**: 2026-04-05

## Entity: Attendance (attendance)
Represents a presence record for an employee on a single working day.

| Field | Type | Attributes | Description |
|-------|------|------------|-------------|
| id | bigint | PK | Unique ID |
| employee_id | bigint | FK (employees) | The employee attending |
| date | date | UI (Composite with employee_id) | The date of record |
| status | enum | present, absent, late | Attendance state |
| notes | text | nullable | Comments/Reasons |
| timestamps | - | - | Laravel created_at/updated_at |

### High-Frequency Queries:
- `where('employee_id', $id)->where('date', $date)`
- `whereHas('employee', fn($q) => $q->where('client_id', $client_id))->where('date', $date)`

---

## Entity: Task (tasks)
Represents an operational unit of work.

| Field | Type | Attributes | Description |
|-------|------|------------|-------------|
| id | bigint | PK | Unique ID |
| client_id | bigint | FK (clients) | Tenant owner |
| assigned_employee_id | bigint | FK (employees), nullable | Person responsible |
| title | string | - | Task summary |
| description | text | nullable | Detailed context |
| due_date | date | nullable | Deadline |
| status | enum | todo, in_progress, done | State flow |
| timestamps | - | - | Laravel created_at/updated_at |

---

## Entity: Asset (assets)
Represents organizational physical property.

| Field | Type | Attributes | Description |
|-------|------|------------|-------------|
| id | bigint | PK | Unique ID |
| client_id | bigint | FK (clients) | Tenant owner |
| employee_id | bigint | FK (employees), nullable | Current custodian |
| type | string | - | Car, Laptop, Phone, etc |
| serial_number | string | Unique within client_id | Tracking ID |
| description | text | nullable | Notes/Brand/Model |
| assigned_date | date | - | Date provided |
| returned_date | date | nullable | Date retrieved |
| timestamps | - | - | Laravel created_at/updated_at |
