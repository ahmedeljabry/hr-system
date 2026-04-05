# Data Model & Components: Super Admin Dashboard Overhaul

This phase extends the existing data layer and introduces new Blade components. No new database tables are required — existing models provide all necessary data.

## Service Layer Extension

### DashboardService (Extended)
* **Path:** `app/Services/DashboardService.php`
* **New Methods:**
  * `getTrendData(string $metric, int $days = 7): array` — Returns daily aggregated values for sparkline charts.
    * Supported metrics: `tenants`, `active_users`, `active_employees`
    * Returns: `['labels' => ['Mon', 'Tue', ...], 'values' => [120, 125, ...], 'current' => 150, 'change_percent' => 5.2]`
  * `getPaginatedClients(array $filters, int $perPage = 25): LengthAwarePaginator`
  * `getPaginatedUsers(array $filters, int $perPage = 25): LengthAwarePaginator`

## New Blade Components

### 1. `x-sparkline-card`
A KPI metric card with an embedded ApexCharts sparkline.
* **Path:** `resources/views/components/sparkline-card.blade.php`
* **Props:**
  * `title` (string) — Metric label (e.g., "Total Tenants")
  * `value` (string|int) — Current metric value
  * `trend` (array) — Array of numeric values for the sparkline
  * `change` (float, optional) — Percentage change (renders as green up / red down)
  * `chart-type` (string) — `line` or `bar`. Default: `line`

### 2. `x-data-table`
A server-side paginated table with inline filtering and sticky headers.
* **Path:** `resources/views/components/data-table.blade.php`
* **Props:**
  * `endpoint` (string) — AJAX URL for fetching paginated data
  * `columns` (array) — Column definitions: `[['key' => 'name', 'label' => 'Name', 'filterable' => true], ...]`
  * `per-page` (int) — Records per page. Default: 25
* **Behavior:**
  * Alpine.js manages pagination state, current page, and filter values
  * Sticky header via `position: sticky; top: 0`
  * Bulk selection with floating action bar
  * Debounced inline filters (300ms)

### 3. `x-sidebar`
A collapsible sidebar navigation with localStorage state persistence.
* **Path:** `resources/views/components/sidebar.blade.php`
* **Props:**
  * `items` (array) — Navigation items: `[['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home', 'badge' => 3], ...]`
* **Behavior:**
  * Toggle button switches between expanded (250px) and collapsed (64px, icon-only)
  * State saved in `localStorage.sidebar_collapsed`
  * Active route highlighted via `request()->routeIs()`
  * Smooth CSS width transition (200ms ease)

### 4. `x-bulk-action-bar`
A floating action bar that appears when table rows are selected.
* **Path:** `resources/views/components/bulk-action-bar.blade.php`
* **Props:**
  * `actions` (array) — Available bulk actions: `[['label' => 'Delete', 'action' => 'delete', 'confirm' => true], ...]`
* **Behavior:** Fixed to bottom of viewport, slides up when `selectedCount > 0`.

## Controller Response Format (AJAX)

Admin controllers return JSON when `Accept: application/json`:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 25,
    "total": 250
  }
}
```

Filter parameters follow: `?filter[name]=john&filter[status]=active&page=2&per_page=25`
