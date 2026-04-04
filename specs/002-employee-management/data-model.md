# Data Model: Employee Management

## Overview

This feature introduces the `Employee` entity, which represents the workforce of a client. It introduces soft deletes (for archiving) and explicitly ties every employee to a specific `client_id` for multi-tenant isolation.

## Entities

### 1. `employees`

Represents an individual worker associated with a client.

**Schema:**

| Column | Type | Modifiers | Description |
|--------|------|-----------|-------------|
| `id` | `bigint unsigned` | PK, Auto-increment | Primary key |
| `client_id` | `bigint unsigned` | FK, Indexed | Associates the employee with a tenant (from `clients` table) |
| `user_id` | `bigint unsigned` | FK, Nullable, Indexed | For Phase 6 (Employee Portal login). Null for now. |
| `name` | `string` | Required | Full name of the employee |
| `position` | `string` | Required | Job title / position |
| `national_id_number` | `string` | Required | Unique per client |
| `national_id_image` | `string` | Nullable | Path to file in `storage/app/private/employees/` |
| `contract_image` | `string` | Nullable | Path to file in `storage/app/private/employees/` |
| `basic_salary` | `decimal(10,2)` | Required | Base monthly salary |
| `hire_date` | `date` | Required | Date the employee started |
| `deleted_at` | `timestamp` | Nullable | Soft deletes column for archiving |
| `created_at` | `timestamp` | | |
| `updated_at` | `timestamp` | | |

**Constraints & Indexes:**
- Unique composite index on `(client_id, national_id_number)`: Ensures no duplicate national IDs within the same tenant.
- Foreign Key `client_id` references `clients(id)` on delete cascade.
- Foreign Key `user_id` references `users(id)` on delete set null.

## Relationships

### `Client` Model Updates

- `hasMany(Employee::class)`
- Represents all employees belonging to the tenant.

### `Employee` Model

- `belongsTo(Client::class)`
- Uses `SoftDeletes` trait.
- Uses `BelongsToTenant` global scope trait (to automatically enforce `where('client_id', auth()->user()->client_id)`).

## State Transitions (Archiving)

- **Active**: `deleted_at` IS NULL. Shows in standard queries.
- **Archived**: `deleted_at` IS NOT NULL. Hidden from standard queries. Restorable via `restore()`.
