# Research & Decision Log: Operations Management

**Feature**: 005-attendance-tasks-assets  
**Date**: 2026-04-05

## Decision: Bulk Attendance Interface
**Rationale**: Client wants to record attendance for *all* employees rapidly (SC-001). Using an individual form submission per employee would violate performance targets.  
**Implementation**: A single `GET` form displaying all employees for a selected date, with a radio-group/dropdown for status and a text field for notes. On `POST`, the `AttendanceService` will perform a bulk `upsert` or individual updates within a database transaction.  
**Alternatives**: Individual entry modals (rejected for UX friction), Excel import (useful but client specifically asked for 'record daily attendance' implying a web UI).

## Decision: Read-Only Task Visibility for Employees
**Rationale**: Per clarification (Session 2026-04-05), employees are not responsible for updating progress; this is purely an awareness tool.  
**Implementation**: Employee-facing controllers will filter tasks by `assigned_employee_id` and own client ID. No `update` or `edit` routes will exist on the `Employee` namespace.  
**Alternatives**: Allowing 'Mark as Done' (rejected by user during clarification).

## Decision: Asset Identifier Logic
**Rationale**: To distinguish identical hardware models (e.g., 5 identical laptops), a serial number field is mandatory (FR-006).  
**Implementation**: The `assets` table will have a nullable `serial_number` string column, unique within the `client_id` scope to prevent internal data entry errors.  
**Alternatives**: Just description string (rejected for lack of accountability).

## Decision: Resource Organization (Multi-Tenancy)
**Rationale**: Constitution III & I require thin controllers and strict isolation.  
**Implementation**: 
- Models: Use `BelongsToClient` trait.
- Services: All methods for retrieval or mutation must accept `client_id` as the primary scoping argument.
- Route: Middleware for Client/Employee roles already exists.
