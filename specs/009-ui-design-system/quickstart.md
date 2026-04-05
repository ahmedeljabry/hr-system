# Quickstart: Design System

## Local Setup
This phase introduces no new backend dependencies. However, you must compile your frontend assets during development.

```bash
# Start your Laravel backend
php artisan serve

# In a separate terminal, compile Tailwind incrementally
npm run dev
```

## Creating a new component
When adding a new atomic component (e.g., a badge), create it in `resources/views/components/badge.blade.php`.

Always remember to use logical CSS properties (e.g., `ps-2` instead of `pl-2`) so the component handles English LTR and Arabic RTL out-of-the-box!
