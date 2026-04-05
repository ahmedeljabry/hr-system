# Quickstart: Employee Portal & Micro-Interactions

## Prerequisites
- Phase 009 (Design System) must be completed.
- Phase 010 (Auth Views) should be completed.
- Phases 011-012 recommended for consistent component reuse.

## Local Setup

```bash
# Run the notification migration
php artisan migrate

# Start Laravel backend
php artisan serve

# Compile assets with hot reload
npm run dev
```

## Testing Mobile Layouts

1. Open Chrome DevTools (F12) → Toggle Device (Ctrl+Shift+M)
2. Select a mobile device (e.g., iPhone 12, 390px width)
3. Log in as an Employee
4. Verify:
   - Bottom navigation bar is visible and fixed
   - All content is single-column with no horizontal scrolling
   - Touch targets are at least 44px
   - Dashboard renders cleanly

## Testing Payslip Receipt View

1. Navigate to Employee → Payslips → select a payslip
2. Verify receipt-style layout with clear sections
3. Click "Print" or use Ctrl+P
4. Verify the print preview shows a clean receipt with no navigation
5. On mobile, verify earnings/deductions sections are collapsible

## Testing Notification Center

1. Generate test notifications (leave approval, task assignment)
2. Verify the bell icon shows a red badge with the unread count
3. Click the bell — verify the notification panel slides in
4. Click a notification — verify it navigates to the related item
5. Click "Mark all as read" — verify badge count clears

## Testing Empty States

1. Log in as a new employee with no payslips
2. Verify a branded empty state message appears (not a blank page)
3. Check other sections: notifications, tasks, leaves — all should show branded empty states

## RTL Testing

Switch to Arabic and verify:
- Bottom navigation mirrors correctly
- Payslip receipt layout reads right-to-left
- Notification panel slides from the left side
- All text alignment and spacing follows logical properties
