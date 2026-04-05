# Data Model: Employee Portal

**Feature**: 006-employee-portal  
**Date**: 2026-04-05

## New Entity

### Announcement

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| client_id | BIGINT UNSIGNED | FK → clients.id, NOT NULL, INDEX | Tenant scope — which company owns this announcement |
| title | VARCHAR(255) | NOT NULL | Announcement headline |
| body | TEXT | NOT NULL | Plain text content, line breaks preserved on display |
| published_at | TIMESTAMP | NOT NULL, DEFAULT NOW(), INDEX | When the announcement was published (supports future scheduling) |
| created_at | TIMESTAMP | Laravel default | Record creation time |
| updated_at | TIMESTAMP | Laravel default | Record last update time |

**Indexes**:
- `client_id` — tenant-scoped queries
- `published_at` — ordering (DESC) for feed display
- Composite: `(client_id, published_at)` — efficient tenant-scoped chronological listing

**Relationships**:
- `Announcement belongsTo Client`
- `Client hasMany Announcements`

**Validation Rules**:
- `title`: required, string, max:255
- `body`: required, string, max:5000
- `published_at`: nullable, date (defaults to now if not provided)

---

## Existing Entities (Read-Only Usage)

The following entities are NOT modified but are queried by the Employee Portal dashboard:

### Task (existing)
- Queried: `WHERE employee_id = ? AND status != 'done'` → pending count
- No schema changes

### Asset (existing)
- Queried: `WHERE employee_id = ?` → assigned count
- No schema changes

### Payslip (existing)
- Queried: `WHERE employee_id = ? ORDER BY created_at DESC LIMIT 1` → latest net salary
- No schema changes

### Employee (existing)
- Queried: Profile view reads `name, position, hire_date, basic_salary, national_id_image, contract_image`
- No schema changes

### Leave Balance / Leave Request (Phase 4 — not yet implemented)
- Queried: Scaffold with empty state; will integrate when Phase 4 tables exist
- No schema changes (tables may not exist yet)

---

## Entity Relationship Summary

```
Client (1) ──── (N) Announcement
Client (1) ──── (N) Employee
Employee (1) ── (N) Task         [read by dashboard]
Employee (1) ── (N) Asset        [read by dashboard]
Employee (1) ── (N) Payslip      [read by dashboard]
```
