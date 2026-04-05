# Quickstart: Super Admin Dashboard Overhaul

## Prerequisites
- Phase 009 (Design System) must be completed.
- Phase 010 (Auth Views) should be completed for consistent layout patterns.

## Local Setup

```bash
# Install ApexCharts
npm install apexcharts

# Start Laravel backend
php artisan serve

# Compile assets with hot reload
npm run dev
```

## Testing Dashboard Widgets

1. Log in as a Super Admin user
2. Navigate to the admin dashboard: `http://localhost:8000/admin/dashboard`
3. Verify sparkline charts render on KPI cards
4. Verify data tables support pagination and inline filtering

## Testing Sidebar

1. From any admin page, click the sidebar collapse toggle
2. Verify sidebar collapses to icon-only mode (64px width)
3. Refresh the page — sidebar should remain collapsed (localStorage persistence)
4. Toggle back to expanded — refresh again to verify persistence

## Testing Pagination

1. Navigate to a table view with > 25 records (e.g., Clients list)
2. Verify page controls load correctly
3. Type in an inline filter — verify filtered results load without full page refresh
4. Verify sticky headers remain visible when scrolling down

## RTL Testing
Switch language to Arabic to verify sidebar positions to the inline-start side and table/chart layouts mirror correctly.
