# Quickstart: Authentication & Public Views Polish

## Prerequisites
- Phase 009 (Design System) must be completed — HSL tokens, premium fonts, and atomic components available.

## Local Setup

```bash
# Start your Laravel backend
php artisan serve

# In a separate terminal, compile assets with hot reload
npm run dev
```

## Testing Auth Views

Navigate to these routes to verify the redesigned views:
- **Login**: `http://localhost:8000/login`
- **Register**: `http://localhost:8000/register`
- **Password Reset**: `http://localhost:8000/password/reset`
- **Error Pages**: Force errors via:
  - 404: Visit any non-existent route (e.g., `/nonexistent-page`)
  - 403: Access a route without proper authorization
  - 500: Temporarily trigger a server error in a test route

## Mobile Testing
Use browser DevTools responsive mode (Chrome: F12 → Toggle Device, or Ctrl+Shift+M) to verify:
- At widths < 768px, the imagery panel is hidden
- Auth form centers with proper padding
- Touch targets meet 44px minimum

## RTL Testing
Switch language to Arabic via the language switcher, or set `dir="rtl"` on the `<html>` tag to verify mirrored layouts.
