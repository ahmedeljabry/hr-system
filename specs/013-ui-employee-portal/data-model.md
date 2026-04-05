# Data Model & Components: Employee Portal & Micro-Interactions

This phase introduces one new database table (`notifications`) and several new Blade components.

## Database Schema

### `notifications` Table (New)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `bigint unsigned` | PK, auto-increment | Primary key |
| `employee_id` | `bigint unsigned` | FK → employees(id), indexed | Owning employee |
| `type` | `string(50)` | NOT NULL | Notification type: `leave_approval`, `task_assigned`, `announcement` |
| `title` | `string(255)` | NOT NULL | Notification title |
| `message` | `text` | NOT NULL | Notification body text |
| `read_at` | `timestamp` | NULLABLE | When the notification was read (null = unread) |
| `related_type` | `string(100)` | NULLABLE | Polymorphic: related model class |
| `related_id` | `bigint unsigned` | NULLABLE | Polymorphic: related model ID |
| `created_at` | `timestamp` | NOT NULL | When notification was created |
| `updated_at` | `timestamp` | NOT NULL | Last update |

**Indexes:** `employee_id`, `(employee_id, read_at)` composite for unread count queries.

**Tenant Isolation:** Notifications are scoped by `employee_id`, and employees are scoped by `client_id`. Query chain: `Notification → Employee → Client` ensures no cross-tenant leakage.

### Eloquent Model: `Notification`
* **Path:** `app/Models/Notification.php`
* **Relationships:**
  * `employee()` — BelongsTo Employee
  * `related()` — MorphTo (LeaveRequest, Task, Announcement)
* **Scopes:**
  * `scopeUnread($query)` — `whereNull('read_at')`
  * `scopeForEmployee($query, $employeeId)` — `where('employee_id', $id)`

## Service Layer

### NotificationService (New)
* **Path:** `app/Services/NotificationService.php`
* **Methods:**
  * `getUnreadCount(int $employeeId): int`
  * `getNotifications(int $employeeId, int $perPage = 20): LengthAwarePaginator`
  * `markAsRead(int $notificationId, int $employeeId): void`
  * `markAllAsRead(int $employeeId): void`
  * `createNotification(int $employeeId, string $type, string $title, string $message, ?Model $related = null): Notification`

## New Blade Components

### 1. `x-payslip-receipt`
A structured receipt-style payslip display.
* **Path:** `resources/views/components/payslip-receipt.blade.php`
* **Props:**
  * `payslip` (Payslip model) — The payslip data to render
  * `printable` (bool) — Whether to show print button. Default: `true`
* **Sections:** Company header, pay period bar, earnings table, deductions table, net salary highlight, footer.
* **Mobile:** Earnings and deductions sections are collapsible accordions.

### 2. `x-notification-panel`
A sliding notification center panel.
* **Path:** `resources/views/components/notification-panel.blade.php`
* **Props:**
  * `notifications` (Collection) — Paginated notification items
* **Behavior:** Slides from inline-end, lists notification items with type icon, title, time-ago stamp. Click to navigate to related entity. "Mark all read" button at top.

### 3. `x-notification-bell`
A bell icon with unread badge count.
* **Path:** `resources/views/components/notification-bell.blade.php`
* **Props:**
  * `count` (int) — Unread notification count
* **Behavior:** Shows red badge with count when > 0. Click toggles notification panel.

### 4. `x-mobile-nav`
A bottom navigation bar for mobile viewports.
* **Path:** `resources/views/components/mobile-nav.blade.php`
* **Props:**
  * `items` (array) — Navigation items: `[['label' => 'Dashboard', 'route' => 'employee.dashboard', 'icon' => 'home'], ...]`
* **Behavior:** Fixed to bottom of viewport. 5 icon-label buttons. Active route highlighted. Hidden on desktop (> 768px).

### 5. `x-empty-state`
A branded empty state placeholder.
* **Path:** `resources/views/components/empty-state.blade.php`
* **Props:**
  * `icon` (string) — Icon name or SVG
  * `title` (string) — Empty state heading
  * `message` (string) — Descriptive text
  * `action-url` (string, optional) — CTA button URL
  * `action-label` (string, optional) — CTA button text

## Print Stylesheet

* **Path:** `resources/css/print.css`
* **Rules:**
  * Hides: navigation, sidebar, buttons, footer, notification bell
  * Shows: payslip receipt content only
  * Sets: A4 margins, proper font sizes, branded header
  * Prevents: page breaks within sections
