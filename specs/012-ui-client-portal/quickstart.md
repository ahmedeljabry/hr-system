# Quickstart: Client Portal UI Refinement

## Prerequisites
- Phase 009 (Design System) must be completed.
- Phase 010 (Auth Views) should be completed.
- Phase 011 (Super Admin Dashboard) recommended — shares sidebar and table patterns.

## Local Setup

```bash
# Start Laravel backend
php artisan serve

# Compile assets with hot reload
npm run dev
```

## Testing Employee Directory

1. Log in as a Client user with employees
2. Navigate to the employee directory
3. Verify grid view displays employee cards with avatars/initials
4. Click the list toggle — verify table view renders without page reload
5. Refresh the page — verify the view mode persists (localStorage)

## Testing Slide-Over Panels

1. From the employee directory, click "Add Employee"
2. Verify a slide-over panel animates in from the right (or left in RTL)
3. Click outside the panel — verify it closes
4. Open again, fill in a field, then try to close — verify unsaved changes prompt

## Testing File Uploads

1. Navigate to an employee's files section
2. Drag a file over the upload zone — verify the zone highlights
3. Drop the file — verify the progress bar animates
4. Verify the file appears in the list after upload completes

## RTL Testing
Switch to Arabic and verify slide-over panels animate from the left side, grid cards flow right-to-left, and all spacing uses logical properties.
