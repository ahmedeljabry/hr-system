# Quickstart: Foundation & Authentication

**Feature**: `001-foundation-auth`
**Date**: 2026-04-04

## Prerequisites

- PHP 8.3+
- Composer 2.x
- MySQL 8.0+
- Node.js 18+ (for asset compilation)

## Setup

```bash
# 1. Clone and checkout feature branch
git checkout 001-foundation-auth

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hr_system
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Create MySQL database (ensure utf8mb4)
mysql -u root -e "CREATE DATABASE hr_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Run migrations
php artisan migrate

# 7. Seed super admin
php artisan db:seed --class=SuperAdminSeeder

# 8. Install frontend dependencies and compile
npm install
npm run dev

# 9. Start development server
php artisan serve
```

## Verification Checklist

After setup, verify each user story works:

### ✅ US1: Client Registration
1. Visit `http://localhost:8000/register`
2. Fill: اسم, بريد إلكتروني, كلمة مرور, اسم الشركة
3. Submit → should redirect to `/client/dashboard`
4. Check DB: `users` table has new row with `role=client`
5. Check DB: `clients` table has new row with `status=active`

### ✅ US2: Login/Logout
1. Logout → visit `http://localhost:8000/login`
2. Enter credentials → should redirect to `/client/dashboard`
3. Click "تسجيل خروج" → should redirect to `/login`
4. Try accessing `/client/dashboard` → should redirect to `/login`

### ✅ US3: Role-Based Protection
1. Login as client → try visiting `/admin/clients` → should get 403
2. Login as super_admin → visit `/admin/clients` → should see client list
3. Login as super_admin → visit `/client/dashboard` → should work (full access)

### ✅ US4: Subscription Management
1. Login as super_admin → visit `/admin/clients`
2. Click "تعليق" on a client → status should change to "معلّق"
3. Login as that client → should redirect to `/subscription/renewal`
4. Super admin restores to "نشط" → client can access dashboard again

### ✅ US5: Super Admin Seeding
1. Verify super_admin exists: `php artisan tinker`
   → `User::where('role', 'super_admin')->exists()` → `true`
2. Run seeder again → should print warning, not create duplicate

## Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `admin@hr-system.com` | `password` (change in production!) |

## Key URLs

| URL | Role | Description |
|-----|------|-------------|
| `/login` | Public | صفحة تسجيل الدخول |
| `/register` | Public | صفحة التسجيل |
| `/admin/dashboard` | super_admin | لوحة المدير |
| `/admin/clients` | super_admin | إدارة العملاء |
| `/client/dashboard` | client | لوحة العميل |
| `/employee/dashboard` | employee | لوحة الموظف |
| `/subscription/renewal` | client (inactive) | صفحة التجديد |
