# Quickstart: Operations Management

**Feature**: 005-attendance-tasks-assets  
**Date**: 2026-04-05

## Client (Write) Routes
A Client can access the operational management modules directly from their dashboard.

| Feature | URL Path | Method | Key Action |
|---------|----------|--------|------------|
| Attendance | `/client/attendance` | GET | List/Filter by date |
| Attendance | `/client/attendance/store` | POST | Bulk record statuses |
| Tasks | `/client/tasks` | GET | View all |
| Tasks | `/client/tasks/create` | POST | Define & Assign |
| Assets | `/client/assets` | GET | Inventory list |
| Assets | `/client/assets/store` | POST | Assign to employee |

## Employee (Read) Routes
An Employee can see their delegated responsibilities via their own portal.

| URL Path | Content | Permissions |
|----------|---------|-------------|
| `/employee/tasks` | Tasks assigned to the authenticated user | Read-Only |
| `/employee/assets` | Physical property currently assigned to user | Read-Only |

## Key Logic (Services)
All logic is centralized to satisfy **Constitution Principle III**:

1. **AttendanceService**: 
   - `getAttendanceForDate(Client $client, Carbon $date)`
   - `bulkUpdateAttendance(Client $client, array $data)` (handles multiple employee IDs at once)
2. **TaskService**:
   - `createTask(array $data, Client $client)`
   - `getTasksForEmployee(Employee $employee)`
3. **AssetService**:
   - `assignAsset(Asset $asset, Employee $employee)`
   - `returnAsset(Asset $asset)`
